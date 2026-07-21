<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class AprioriHasil extends Model
{
    use BelongsToUmkm;

    protected $table = 'apriori_hasil';

    public $timestamps = false;

    protected $fillable = [
        'keterangan',
        'tgl_proses',
        'min_support',
        'min_confidence',
        'total_data',
        'data_rules',
        'data_hasil',
        'waktu',
        'bulan_data',
        'bulan',
        'nama_bulan',
        'diterapkan',
        'umkm_id',
    ];

    protected function casts(): array
    {
        return [
            'tgl_proses' => 'datetime',
            'total_data' => 'integer',
            'diterapkan' => 'boolean',
        ];
    }

    public function hasilDataset()
    {
        return $this->hasMany(AprioriHasilDataset::class, 'id_hasil');
    }
}

