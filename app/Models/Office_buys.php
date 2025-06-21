<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_buys extends Model
{
    protected $guarded = [];

    public function list_item(){
		return $this->hasMany(Office_cs_items::class,'cs_id','id')
		->select(
			'office_cs_item.*',
			'office_customer_item.*',
			'office_cs_item.id as id',
			'office_cs_item.is_active as is_active',
			'office_category.name as category_name',
			'office_brand.name as brand_name',
			'office_type.name as type_name',
			'office_model.name as model_name'
		)
		->leftJoin('office_customer_item','office_cs_item.item_id','=','office_customer_item.id')
		->leftJoin('office_category','office_customer_item.category_id','=','office_category.id')
		->leftJoin('office_brand','office_customer_item.brand_id','=','office_brand.id')
		->leftJoin('office_type','office_customer_item.type_id','=','office_type.id')
		->leftJoin('office_model','office_customer_item.model_id','=','office_model.id')
		->orderBy('office_cs_item.id','asc');
	}

	public static function getNextCode() {

		$last_count = 1;
		$year = DATE("Y");
		$month = DATE("m");

		$customer = Office_css::select('code')
		->orderBy('code', 'desc')
		->first();

		if($customer) {
			$data = explode("/", $customer->code);
			$last_count = intval($data[3]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%06d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return "SX/".$year."/".$month."/".$COUNTER;
	}
}
