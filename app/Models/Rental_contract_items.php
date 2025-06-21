<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental_contract_items extends Model
{
    protected $guarded = [];

    public function rental_contract() {
      return $this->belongsTo(Rental_contracts::class, 'rental_contract_id', 'id');
    }
}
