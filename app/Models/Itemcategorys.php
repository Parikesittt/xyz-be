<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itemcategorys extends Model
{
    protected $table = 'itemcategory';
    protected $guarded = [];
    protected $appends = ['attachment_type_name', 'itemgroup_name'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	public function itemgroup() {
      return $this->belongsTo(Itemgroups::class);
    }
	public function subcategorys(){
        return $this->hasMany(Subcategorys::class, 'itemgcategory_id', 'id');
    }
	public function purchase_request_months(){
        return $this->hasMany(Purchase_request_months::class, 'itemgcategory_id', 'id');
    }
	public function items(){
        return $this->hasMany(Items::class, 'itemgcategory_id', 'id');
    }


	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getItemgroupNameAttribute() {
       $itemgroup = $this->itemgroup()->first();
       return ($itemgroup?$itemgroup->name:null);
    }
	public static function getNextCounterId($itemgroup) {

      $last_count = 1;

	  $CODECATEGORY = $itemgroup;

      // get last count
      $itemcategory = Itemcategorys::select('code')
        ->where('code','like', $CODECATEGORY.'%')
        ->orderBy('code', 'desc')
        ->first();


	  if($itemcategory) {
        // Convert string to integer before adding
        $last_number = intval(substr($itemcategory->code, -2));
        $last_count = $last_number + 1;
      }

        // Format the counter with leading zeros
      $COUNTER = sprintf('%02d', $last_count);

      return $CODECATEGORY.$COUNTER;
    }
}
