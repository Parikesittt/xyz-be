<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    protected $table = 'unit';
    protected $guarded = [];

    public function items() {
        return $this->hasMany(Items::class, 'unit_id', 'id');
    }
}
