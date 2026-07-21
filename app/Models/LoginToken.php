<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginToken extends Model
{
    use HasFactory;

    protected $fillable = ['token', 'user_id', 'umkm_code', 'expires_at'];

    public $timestamps = true;
}
