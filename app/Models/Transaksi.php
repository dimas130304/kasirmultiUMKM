<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class Transaksi extends Model
{
    use BelongsToUmkm;

    protected $table = 'transaksi';

    public $timestamps = false;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = null;

    protected $fillable = [
        'no_bon',
        'kasir_id',
        'customer_id',
        'atas_nama',
        'pesanan',
        'status',
        'diskon',
        'pajak',
        'voucher',
        'grandmodal',
        'grandtotal',
        'total_qty',
        'dibayar',
        'status_updated_at',
        'status_updated_by',
        'created_at',
        'date',
        'periode',
        'year',
        'umkm_id',
    ];

    protected function casts(): array
    {
        return [
            'diskon' => 'integer',
            'pajak' => 'integer',
            'voucher' => 'integer',
            'grandmodal' => 'integer',
            'grandtotal' => 'integer',
            'total_qty' => 'integer',
            'dibayar' => 'integer',
            'created_at' => 'datetime',
            'date' => 'date',
        ];
    }

    public function kasir()
    {
        return $this->belongsTo(Login::class, 'kasir_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function produk()
    {
        return $this->hasMany(TransaksiProduk::class, 'no_bon', 'no_bon');
    }
}

