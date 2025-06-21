<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales_tax_groups extends Model
{
    protected $guarded = [];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	public function itemcategorys(){
        return $this->hasMany(Itemcategorys::class, 'itemgroup_id', 'id');
    }
	public function subcategorys(){
        return $this->hasMany(Subcategorys::class, 'itemgroup_id', 'id');
    }
	public function items(){
        return $this->hasMany(Items::class, 'itemgroup_id', 'id');
    }
	public function salesinvoices() {
      return $this->belongsTo(Salesinvoices::class, 'ppn_type', 'id');
    }
	public function inventory() {
      return $this->belongsTo(Inventorys::class, 'id', 'ppn_type');
    }
}
