<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class Menu extends Model
{
    use BelongsToUmkm;

    protected $table = 'menu';

    public $timestamps = false;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = null;

    protected $fillable = [
        'id_kategori',
        'kode_menu',
        'nama',
        'harga_pokok',
        'harga_jual',
        'stok',
        'keterangan',
        'gambar',
        'created_at',
        'umkm_id',
    ];

    protected function casts(): array
    {
        return [
            'harga_pokok' => 'integer',
            'harga_jual' => 'integer',
            'stok' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function menuStok()
    {
        return $this->hasMany(MenuStok::class, 'menu_id');
    }

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class, 'id_menu');
    }
}

