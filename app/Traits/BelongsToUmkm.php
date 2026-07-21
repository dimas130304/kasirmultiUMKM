<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToUmkm
{
    protected static function bootBelongsToUmkm(): void
    {
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->umkm_id) {
                if (!isset($model->umkm_id) || is_null($model->umkm_id)) {
                    $model->umkm_id = Auth::user()->umkm_id;
                }
            }
        });

        static::addGlobalScope('umkm', function (Builder $builder) {
            if (Auth::check() && Auth::user()->umkm_id) {
                $builder->where($builder->getQuery()->from . '.umkm_id', Auth::user()->umkm_id);
            }
        });
    }
}
