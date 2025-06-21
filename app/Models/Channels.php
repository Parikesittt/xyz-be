<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channels extends Model
{
    protected $table = 'channel';
    protected $fillable = [
        'id',
        'code',
        'name',
        'saved_id',
        'update_id',
        'company_id',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function customers() {
      return $this->hasMany(Customers::class, 'channel_id', 'id');
    }
}
