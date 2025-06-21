<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_products extends Model
{
    protected $guarded = [];
    protected $appends = ['item_name', 'category_name', 'brand_name', 'type_name', 'model_name'];

    public function itemName(){
		return $this->belongsTo(Items::class, 'item_id', 'id');
	}

	public function getItemNameAttribute() {
		$item_name = $this->itemName()->first();
		return ($item_name?$item_name->name:null);
	}

	public function categoryName(){
		return $this->belongsTo(Office_categorys::class, 'category_id', 'id');
	}

	public function getCategoryNameAttribute() {
		$category_name = $this->categoryName()->first();
		return ($category_name?$category_name->name:null);
	}

	public function brandName(){
		return $this->belongsTo(Office_brands::class, 'brand_id', 'id');
	}

	public function getBrandNameAttribute() {
		$brand_name = $this->brandName()->first();
		return ($brand_name?$brand_name->name:null);
	}

	public function typeName(){
		return $this->belongsTo(Office_types::class, 'type_id', 'id');
	}

	public function getTypeNameAttribute() {
		$type_name = $this->typeName()->first();
		return ($type_name?$type_name->name:null);
	}

	public function modelName(){
		return $this->belongsTo(Office_models::class, 'model_id', 'id');
	}

	public function getModelNameAttribute() {
		$model_name = $this->modelName()->first();
		return ($model_name?$model_name->name:null);
	}

	public static function getNextCode() {

		$last_count = 1;

		$model = Office_products::select('code')
		->orderBy('code', 'desc')
		->first();

		if($model) {
			$data = explode("P", $model->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%06d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return "P".$COUNTER;
	}
}
