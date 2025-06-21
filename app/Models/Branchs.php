<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branchs extends Model
{
    protected $table = 'branch';
    protected $fillable = [
        'id',
        'accountNum',
        'code',
        'name',
        'saved_id',
        'updated_id',
        'company_id',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
