<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $table = 'umkm';

    public $timestamps = false;

    protected $fillable = [
        'kode_umkm',
        'nama_umkm',
        'alamat_umkm',
        'nama_pemilik',
        'email',
        'telepon',
        'status',
        'created_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_umkm)) {
                $maxId = \DB::table('umkm')->max('id') ?? 0;
                $nextId = $maxId + 1;
                $model->kode_umkm = 'UMKM' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function users()
    {
        return $this->hasMany(Login::class, 'umkm_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'umkm_id');
    }

    public function profilToko()
    {
        return $this->hasOne(ProfilToko::class, 'umkm_id');
    }

    public function getStorePhotoAttribute()
    {
        foreach ($this->users->sortByDesc('id') as $user) {
            if ($user->level === 'Admin' && $user->foto && $user->foto !== '-' && file_exists(public_path('assets/image/user/' . $user->foto))) {
                return $user->foto;
            }
        }
        return null;
    }
}
