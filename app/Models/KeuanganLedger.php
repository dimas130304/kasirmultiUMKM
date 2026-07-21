<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToUmkm;

class KeuanganLedger extends Model
{
    use SoftDeletes, BelongsToUmkm;

    protected $table = 'keuangan_ledger';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'no_ledger',
        'keterangan',
        'jenis',
        'umkm_id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function keuanganLainnya()
    {
        return $this->hasMany(KeuanganLainnya::class, 'no_ledger', 'no_ledger');
    }
}

