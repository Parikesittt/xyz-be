<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse_project_groups extends Model
{
    protected $guarded = [];

    public function customers() {
        return $this->hasMany(Customers::class, 'customer_id', 'id');
      }
      
      public function warehouse() {
        return $this->belongsTo(Warehouses::class, 'warehouse_id', 'id');
      }
}
