<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class MenuStok extends Model
{
    use BelongsToUmkm;

    protected $table = 'menu_stok';

    public $timestamps = false;

    protected $fillable = [
        'menu_id',
        'stok_awal',
        'stok_akhir',
        'date',
        'periode',
        'umkm_id',
    ];

    protected function casts(): array
    {
        return [
            'stok_awal' => 'integer',
            'stok_akhir' => 'integer',
            'date' => 'date',
        ];
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}

