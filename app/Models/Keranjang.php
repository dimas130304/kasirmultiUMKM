<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class Keranjang extends Model
{
    use BelongsToUmkm;

    protected $table = 'keranjang';

    public $timestamps = false;

    protected $fillable = [
        'id_menu',
        'kode_menu',
        'kategori',
        'nama',
        'keterangan',
        'gambar',
        'qty',
        'harga_beli',
        'harga_jual',
        'login_id',
        'umkm_id',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'harga_beli' => 'integer',
            'harga_jual' => 'integer',
        ];
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu');
    }

    public function login()
    {
        return $this->belongsTo(Login::class, 'login_id');
    }
}

