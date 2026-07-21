<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class Customer extends Model
{
    use BelongsToUmkm;

    protected $table = 'customer';

    public $timestamps = false;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = null;

    protected $fillable = [
        'kode_customer',
        'nama',
        'alamat',
        'hp',
        'keterangan',
        'created_at',
        'umkm_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'customer_id');
    }
}

