<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanBiaya extends Model
{
    protected $table = 'laporan_biaya';

    protected $fillable = [
        'laporan_id', 'jenis_biaya', 'jumlah',
        'keterangan', 'file_bukti_path', 'file_bukti_name', 'file_bukti_type',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
        ];
    }

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class);
    }

    public function getBuktUrlAttribute(): ?string
    {
        if ($this->file_bukti_path) {
            return route('storage.file', ['path' => $this->file_bukti_path]);
        }
        return null;
    }
}
