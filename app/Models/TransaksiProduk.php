<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class TransaksiProduk extends Model
{
    use BelongsToUmkm;

    protected $table = 'transaksi_produk';

    public $timestamps = false;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = null;

    protected $fillable = [
        'no_bon',
        'kode_menu',
        'kategori',
        'nama_menu',
        'qty',
        'harga_beli',
        'harga_jual',
        'keterangan',
        'pesan',
        'created_at',
        'date',
        'periode',
        'year',
        'umkm_id',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'harga_beli' => 'integer',
            'harga_jual' => 'integer',
            'created_at' => 'datetime',
            'date' => 'date',
        ];
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'no_bon', 'no_bon');
    }
}

