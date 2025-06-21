<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth_token_qontak_whatsapps extends Model
{
    protected $table = 'auth_token_qontak_whatsapp';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'authorization_type',
        'authorization_value',
        'is_active',
        'created_at',
        'updated_at',
    ];
}
