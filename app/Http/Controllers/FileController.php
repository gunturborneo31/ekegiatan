<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Laporan;
use App\Models\LaporanDokumentasi;
use App\Models\LaporanBiaya;
use App\Models\Kegiatan;

class FileController extends Controller
{
    public function show(Request $request, string $path)
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        // Validate the file path and user access
        $decodedPath = urldecode($path);

        if (! Storage::disk('private')->exists($decodedPath)) {
            abort(404);
        }

        // Check access rights based on path prefix
        $this->authorizeFilePath($user, $decodedPath);

        return Storage::disk('private')->response($decodedPath);
    }

    protected function authorizeFilePath($user, string $path): void
    {
        // Super admin can access all files
        if ($user->isSuperAdmin()) return;

        // Pimpinan can access all files (read-only)
        if ($user->isPimpinan()) return;

        // For surat_kegiatan files
        if (str_starts_with($path, 'surat_kegiatan/')) {
            $parts = explode('/', $path);
            $kegiatanId = $parts[1] ?? null;
            if ($kegiatanId) {
                $kegiatan = Kegiatan::find($kegiatanId);
                if ($kegiatan) {
                    if ($user->isAdminBidang() && $user->bidang_id === $kegiatan->bidang_id) return;
                    if ($user->isStaff()) {
                        $assigned = $kegiatan->kegiatanStaff()->where('user_id', $user->id)->exists();
                        if ($assigned) return;
                    }
                }
            }
        }

        // For laporan files
        if (str_starts_with($path, 'laporan/')) {
            $parts = explode('/', $path);
            $laporanId = $parts[1] ?? null;
            if ($laporanId) {
                $laporan = Laporan::with('kegiatanStaff.kegiatan')->find($laporanId);
                if ($laporan) {
                    $ks = $laporan->kegiatanStaff;
                    if ($user->isAdminBidang() && $user->bidang_id === $ks->kegiatan->bidang_id) return;
                    if ($user->isStaff() && $ks->user_id === $user->id) return;
                }
            }
        }

        abort(403);
    }
}
