<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business_types extends Model
{
    protected $table = 'business_type';
    protected $fillable = [
        'id',
        'code',
        'name',
        'is_active',
        'company_id',
        'saved_id',
        'updated_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
