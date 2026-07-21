<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Login extends Authenticatable
{
    protected $table = 'login';

    public $timestamps = false;

    protected $fillable = [
        'user',
        'pass',
        'nama_user',
        'alamat',
        'email',
        'telepon',
        'foto',
        'level',
        'tgl_bergabung',
        'deleted_at',
        'email_login',
        'umkm_id',
    ];

    protected $hidden = [
        'pass',
    ];

    public function getAuthPassword(): string
    {
        return (string) $this->pass;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('deleted_at');
    }

    public function isSuperAdmin(): bool
    {
        return $this->level === 'SuperAdmin';
    }

    public function isAdmin(): bool
    {
        return $this->level === 'Admin';
    }

    public function isKasir(): bool
    {
        return $this->level === 'Kasir';
    }

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'login_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'kasir_id');
    }

    public function keuanganLainnya()
    {
        return $this->hasMany(KeuanganLainnya::class, 'user_id');
    }
}

