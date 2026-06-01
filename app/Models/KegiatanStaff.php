<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KegiatanStaff extends Model
{
    protected $table = 'kegiatan_staff';

    protected $fillable = [
        'kegiatan_id', 'user_id', 'status_laporan',
        'catatan_revisi', 'verified_at', 'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function laporan(): HasOne
    {
        return $this->hasOne(Laporan::class);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status_laporan) {
            'belum'     => 'red',
            'draf'      => 'gray',
            'submitted' => 'blue',
            'disetujui' => 'green',
            'revisi'    => 'orange',
            default     => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status_laporan) {
            'belum'     => 'Belum Diisi',
            'draf'      => 'Draf',
            'submitted' => 'Submitted',
            'disetujui' => 'Disetujui',
            'revisi'    => 'Perlu Revisi',
            default     => '-',
        };
    }
}
