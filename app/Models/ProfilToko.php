<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class ProfilToko extends Model
{
    use BelongsToUmkm;

    protected $table = 'profil_toko';

    public $timestamps = false;

    protected $fillable = [
        'nama_toko',
        'alamat_toko',
        'telepon_toko',
        'email_toko',
        'pemilik_toko',
        'website_toko',
        'tgl_update',
        'os',
        'print',
        'print_default',
        'driver',
        'footer_struk',
        'nama_bank',
        'no_rekening',
        'atas_nama_rekening',
        'catatan_pembayaran',
        'pajak',
        'voucher',
        'diskon',
        'user_id',
        'umkm_id',
    ];

    protected function casts(): array
    {
        return [
            'tgl_update' => 'datetime',
            'os' => 'integer',
            'print' => 'integer',
            'print_default' => 'integer',
            'pajak' => 'integer',
            'voucher' => 'integer',
            'diskon' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(Login::class, 'user_id');
    }
}

