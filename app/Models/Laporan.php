<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laporan extends Model
{
    protected $fillable = [
        'kegiatan_staff_id', 'kesimpulan',
        'total_transport', 'total_makan', 'total_penginapan',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'total_transport' => 'decimal:2',
            'total_makan' => 'decimal:2',
            'total_penginapan' => 'decimal:2',
        ];
    }

    public function kegiatanStaff(): BelongsTo
    {
        return $this->belongsTo(KegiatanStaff::class);
    }

    public function dokumentasi(): HasMany
    {
        return $this->hasMany(LaporanDokumentasi::class)->orderBy('urutan');
    }

    public function biaya(): HasMany
    {
        return $this->hasMany(LaporanBiaya::class);
    }

    public function biayaTransport(): HasMany
    {
        return $this->hasMany(LaporanBiaya::class)->where('jenis_biaya', 'transport');
    }

    public function biayaMakan(): HasMany
    {
        return $this->hasMany(LaporanBiaya::class)->where('jenis_biaya', 'makan');
    }

    public function biayaPenginapan(): HasMany
    {
        return $this->hasMany(LaporanBiaya::class)->where('jenis_biaya', 'penginapan');
    }

    public function getTotalKeseluruhanAttribute(): float
    {
        return (float) $this->total_transport + (float) $this->total_makan + (float) $this->total_penginapan;
    }

    public function recalculateTotals(): void
    {
        $this->total_transport = $this->biayaTransport()->sum('jumlah');
        $this->total_makan = $this->biayaMakan()->sum('jumlah');
        $this->total_penginapan = $this->biayaPenginapan()->sum('jumlah');
        $this->save();
    }
}
