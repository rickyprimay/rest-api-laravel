<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'title',
        'released_year',
        'genre',
        'type',
    ];

    public static $allowedTypes = ['Free', 'Paid'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!in_array($model->type, self::$allowedTypes)) {
                return false;
            }
        });
    }
}
