<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    protected $fillable = [
        'bidang_id', 'nama_kegiatan', 'deskripsi', 'tanggal_berangkat',
        'tanggal_pulang', 'lokasi', 'nomor_surat', 'file_surat',
        'file_surat_name', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_berangkat' => 'date',
            'tanggal_pulang' => 'date',
        ];
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function kegiatanStaff(): HasMany
    {
        return $this->hasMany(KegiatanStaff::class);
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'kegiatan_staff')
            ->withPivot(['status_laporan', 'catatan_revisi', 'verified_at', 'verified_by'])
            ->withTimestamps();
    }

    public function scopeByBidang($query, $bidangId)
    {
        return $query->where('bidang_id', $bidangId);
    }

    public function scopeByStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function getTotalBiayaAttribute(): float
    {
        return $this->kegiatanStaff->sum(function ($ks) {
            return $ks->laporan ? $ks->laporan->total_keseluruhan : 0;
        });
    }
}
