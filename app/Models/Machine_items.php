<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine_items extends Model
{
    protected $guarded = [];

    public function machine() {
      return $this->belongsTo(Machines::class, 'machine_id', 'id');
    }
}
