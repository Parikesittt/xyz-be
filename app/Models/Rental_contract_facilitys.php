<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental_contract_facilitys extends Model
{
    protected $guarded = [];

    public function rental_contract() {
      return $this->belongsTo(Rental_contracts::class, 'rental_contract_id', 'id');
    }
    public function rental_contract_item() {
      return $this->belongsTo(Rental_contract_items::class, 'rental_contract_item_id', 'id');
    }
}
