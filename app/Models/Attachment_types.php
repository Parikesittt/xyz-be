<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment_types extends Model
{
    protected $table = 'attachment_type';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'code',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_active',
    ];

}
