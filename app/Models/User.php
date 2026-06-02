<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'nip', 'jabatan', 'email', 'password',
        'role', 'bidang_id', 'avatar', 'phone', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isPimpinan(): bool { return $this->role === 'pimpinan'; }
    public function isAdminBidang(): bool { return $this->role === 'admin_bidang'; }
    public function isStaff(): bool { return $this->role === 'staff'; }
    public function canView(): bool { return in_array($this->role, ['super_admin', 'pimpinan', 'admin_bidang', 'staff']); }
    public function isReadOnly(): bool { return $this->role === 'pimpinan'; }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    public function kegiatanDibuat(): HasMany
    {
        return $this->hasMany(Kegiatan::class, 'created_by');
    }

    public function kegiatanStaff(): HasMany
    {
        return $this->hasMany(KegiatanStaff::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByBidang($query, $bidangId)
    {
        return $query->where('bidang_id', $bidangId);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return route('storage.file', ['path' => $this->avatar]);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=003580&color=fff';
    }
}
