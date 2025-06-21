<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citys extends Model
{
    protected $table = 'city';
    protected $fillable = [
        'id',
        'name',
        'code',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
