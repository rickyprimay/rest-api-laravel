<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_content',
        'accessed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'id_content');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->accessed_at = now();
        });
    }
}
