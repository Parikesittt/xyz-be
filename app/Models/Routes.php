<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routes extends Model
{
    protected $guarded = [];

    public function roles() {
      return $this->hasMany(Roles::class, 'route_id', 'id');
    }
}
