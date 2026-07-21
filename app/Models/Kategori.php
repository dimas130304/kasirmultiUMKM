<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUmkm;

class Kategori extends Model
{
    use BelongsToUmkm;

    protected $table = 'kategori';

    public $timestamps = false;

    protected $fillable = [
        'kategori',
        'umkm_id',
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'id_kategori');
    }
}

