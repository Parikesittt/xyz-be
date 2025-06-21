<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_type_items extends Model
{
    protected $guarded = [];

    public function items() {
      return $this->belongsTo(Items::class, 'item_code', 'code');
    }
}
