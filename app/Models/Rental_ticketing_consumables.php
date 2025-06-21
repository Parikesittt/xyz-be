<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental_ticketing_consumables extends Model
{
    protected $guarded = [];

    public function rental_ticketing() {
      return $this->belongsTo(Rental_ticketings::class, 'rental_ticketing_id', 'id');
    }
}
