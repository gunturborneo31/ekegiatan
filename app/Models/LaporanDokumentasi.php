<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanDokumentasi extends Model
{
    protected $table = 'laporan_dokumentasi';

    protected $fillable = [
        'laporan_id', 'file_path', 'file_name',
        'file_type', 'file_size', 'keterangan', 'urutan',
    ];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class);
    }

    public function getFileUrlAttribute(): string
    {
        return route('storage.file', ['path' => $this->file_path]);
    }

    public function isImage(): bool
    {
        return in_array(strtolower($this->file_type), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }
}
