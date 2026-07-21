<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class AprioriDataset extends Model
{
    use BelongsToUmkm;

    protected $table = 'apriori_dataset';

    public $timestamps = false;

    protected $fillable = [
        'tgl_transaksi',
        'no_transaksi',
        'dataset',
        'umkm_id',
    ];
}

