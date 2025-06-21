<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule_items extends Model
{
    protected $guarded = [];

    public function schedule() {
      return $this->belongsTo(Schedules::class, 'schedule_id', 'id');
    }
}
