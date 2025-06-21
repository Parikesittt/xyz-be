<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salesmans extends Model
{
    protected $table = 'salesman';
    protected $guarded = [];
    protected $appends = ['attachment_type_name', 'head_glotrade', 'head_sales', 'sales_manager', 'sales_supervisor'];

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
	public function sales_orders(){
        return $this->hasMany(Sales_orders::class, 'sales_id', 'id');
    }
	public function list_item(){
		return $this->hasMany(Salesman_items::class,'salesman_id','id');
	}

 //------------------------------------------------------------------------------------
  // custom fields

	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public static function getNextCounterId() {

		$last_count = 1;
		$CODE = 'SA';

		// get last count
		$salesman = Salesmans::select('accountNum')
						->where('accountNum','like', $CODE.'%')
						->orderBy('accountNum', 'desc')
						->first();

		if($salesman) {
			$data = explode($CODE, $salesman->accountNum);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%04d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$COUNTER;

    }
}
