<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class AprioriHasilDataset extends Model
{
    use BelongsToUmkm;

    protected $table = 'apriori_hasil_dataset';

    public $timestamps = false;

    protected $fillable = [
        'tgl_transaksi',
        'no_transaksi',
        'dataset',
        'id_hasil',
        'umkm_id',
    ];

    protected function casts(): array
    {
        return [
            'id_hasil' => 'integer',
        ];
    }

    public function hasil()
    {
        return $this->belongsTo(AprioriHasil::class, 'id_hasil');
    }
}

