<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item_subcategorys extends Model
{
    protected $table = 'item_subcategory';
    protected $guarded = [];

    public function items(){
        return $this->hasMany(Items::class, 'item_subcategory_id', 'id');
    }
}
