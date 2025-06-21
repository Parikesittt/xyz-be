<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_part_items extends Model
{
    protected $table = 'office_part_item';
    protected $guarded = [];

    public function items() {
      return $this->belongsTo(Items::class, 'code', 'part_for');
    }
}
