<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales_offer_details extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name','itemcategory_name','product_code'];

    public function atttachment_type() {
		return $this->belongsTo(Attachment_types::class);
    }
	public function itemcategory() {
		return $this->belongsTo(Itemcategorys::class);
    }
	public function itemcategorys(){
        return $this->hasMany(Itemcategorys::class, 'purchase_request_id', 'id');
    }
	public function subcategorys(){
        return $this->hasMany(Subcategorys::class, 'purchase_request_id', 'id');
    }
	public function items(){
        return $this->hasMany(Items::class, 'id', 'item_code');
    }
	
	
	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getItemcategoryNameAttribute() {
       $itemcategory = $this->itemcategory()->first();
       return ($itemcategory?$itemcategory->name:null);
    }
	public function getProductCodeAttribute() {
       $items = $this->items()->first();
       return ($items?$items->product_code:null);
    }
}
