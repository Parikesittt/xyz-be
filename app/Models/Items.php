<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $table = 'item';
    protected $guarded = [];
    protected $appends = ['attachment_type_name','itemgroup_name','itemcategory_name','subcategory_name','item_subcategory_name', 'itemgroup_code','supplier_name','brand_name'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	public function unit() {
      return $this->belongsTo(Units::class);
    }
	public function itemgroup() {
      return $this->belongsTo(Itemgroups::class);
    }
	public function itemcategory() {
      return $this->belongsTo(Itemcategorys::class);
    }
	public function subcategory() {
      return $this->belongsTo(Subcategorys::class);
    }
	public function item_subcategory() {
      return $this->belongsTo(Item_subcategorys::class);
    }
	public function inventorys() {
      return $this->hasMany(Inventorys::class, 'item_id', 'id');
    }
	public function unit_converts() {
      return $this->hasMany(Unit_converts::class, 'item_code', 'code');
    }
	public function supplier(){
        return $this->belongsTo(Suppliers::class);
    }
	public function brand(){
        return $this->belongsTo(Brands::class);
    }
	public function office_part_items() {
      return $this->hasMany(Office_part_items::class, 'part_for', 'code');
    }

 //------------------------------------------------------------------------------------
  // custom fields

	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	/* public function getUnitNameAttribute() {
       $unit = $this->unit()->first();
       return ($unit?$unit->unit:null);
    } */
	public function getItemgroupNameAttribute() {
       $itemgroup = $this->itemgroup()->first();
       return ($itemgroup?$itemgroup->name:null);
    }
	public function getItemcategoryNameAttribute() {
       $itemcategory = $this->itemcategory()->first();
       return ($itemcategory?$itemcategory->name:null);
    }
	public function getSubcategoryNameAttribute() {
       $subcategory = $this->subcategory()->first();
       return ($subcategory?$subcategory->name:null);
    }
	public function getItemSubcategoryNameAttribute() {
       $item_subcategory = $this->item_subcategory()->first();
       return ($item_subcategory?$item_subcategory->name:null);
    }
	public function getItemGroupCodeAttribute() {
       $itemgroup_code = $this->itemgroup()->first();
       return ($itemgroup_code?$itemgroup_code->code:null);
    }
	public function getSupplierNameAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->name:null);
    }
	public function getBrandNameAttribute() {
       $brand = $this->brand()->first();
       return ($brand?$brand->name:null);
    }

	public static function getNextCounterId($subcategory) {

      $last_count = 1;

	  $CODECATEGORY = $subcategory;
	  //var_dump($CODECATEGORY);
      // get last count
      $item = Items::select('code')
        ->where('code','like', $CODECATEGORY.'%')
        ->whereRaw('LENGTH(code) = 10')
        ->orderBy('code', 'desc')
        ->first();


	  if($item) {
        $data = explode($CODECATEGORY, $item->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%05d', $last_count);
      $COUNTER = $curr_count;

      return $CODECATEGORY.$COUNTER;
    }
}
