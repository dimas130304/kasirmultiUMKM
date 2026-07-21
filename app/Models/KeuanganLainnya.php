<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class KeuanganLainnya extends Model
{
    use BelongsToUmkm;

    protected $table = 'keuangan_lainnya';

    public $timestamps = false;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = null;

    protected $fillable = [
        'no_ledger',
        'nama_urusan',
        'jenis',
        'jumlah_masuk',
        'jumlah_keluar',
        'created_at',
        'date',
        'periode',
        'year',
        'keterangan',
        'user_id',
        'umkm_id',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_masuk' => 'integer',
            'jumlah_keluar' => 'integer',
            'created_at' => 'datetime',
            'date' => 'date',
        ];
    }

    public function ledger()
    {
        return $this->belongsTo(KeuanganLedger::class, 'no_ledger', 'no_ledger');
    }

    public function user()
    {
        return $this->belongsTo(Login::class, 'user_id');
    }
}

