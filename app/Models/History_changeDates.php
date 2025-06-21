<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History_changeDates extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(Users::class);
    }
}
