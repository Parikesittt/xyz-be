<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier_category_items extends Model
{
    protected $guarded = [];

    public function suppliers() {
      return $this->hasMany(Suppliers::class, 'supplier_id', 'id');
    }
}
