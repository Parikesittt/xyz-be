<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area_locations extends Model
{
    protected $table = 'area_location';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'area',
        'name',
        'company_id',
        'created_at',
        'updated_at',
        'is_active',
    ];
}
