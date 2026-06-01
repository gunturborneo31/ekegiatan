<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bidang extends Model
{
    protected $table = 'bidang';

    protected $fillable = ['nama_bidang', 'kode_bidang', 'deskripsi'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function kegiatan(): HasMany
    {
        return $this->hasMany(Kegiatan::class);
    }
}
