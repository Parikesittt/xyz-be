<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategorys extends Model
{
    protected $table = 'subcategory';
    protected $guarded = [];
    protected $appends = ['attachment_type_name', 'itemgroup_name', 'itemcategory_name'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	public function itemgroup() {
      return $this->belongsTo(Itemgroups::class);
    }
	public function itemcategory() {
      return $this->belongsTo(Itemcategorys::class);
    }
	public function items(){
        return $this->hasMany(Items::class, 'subcategory_id', 'id');
    }
	public function suppliers(){
        return $this->hasMany(Suppliers::class, 'subcategory_id', 'id');
    }

    public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getItemgroupNameAttribute() {
       $itemgroup = $this->itemgroup()->first();
       return ($itemgroup?$itemgroup->name:null);
    }
	public function getItemcategoryNameAttribute() {
       $itemcategory = $this->itemcategory()->first();
       return ($itemcategory?$itemcategory->name:null);
    }
	public static function getNextCounterId($itemcategory) {


      $last_count = 1;

	  $CODECATEGORY = $itemcategory;

      // get last count
      $subcategory = Subcategorys::select('code')
        ->where('code','like', $CODECATEGORY.'%')
        ->orderBy('code', 'desc')
        ->first();


        if($subcategory) {
            // Convert string to integer before adding
            $last_number = intval(substr($subcategory->code, -2));
            $last_count = $last_number + 1;
        }

        // Format the counter with leading zeros
        $COUNTER = sprintf('%02d', $last_count);

      return $CODECATEGORY.$COUNTER;
    }
}
