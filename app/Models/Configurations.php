<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configurations extends Model
{
    protected $fillable = [
        'id',
        'key',
        'value',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
