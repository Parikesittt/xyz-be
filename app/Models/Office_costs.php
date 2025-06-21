<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_costs extends Model
{
    protected $table = 'office_cost';
    protected $guarded = [];
    protected $appends = ['category_name', 'brand_name', 'type_name', 'model_name'];

    public function categoryName(){
		return $this->belongsTo(Office_categorys::class, 'category_id', 'id');
	}

	public function brandName(){
		return $this->belongsTo(Office_brands::class, 'brand_id', 'id');
	}

	public function typeName(){
		return $this->belongsTo(Office_types::class, 'type_id', 'id');
	}

	public function modelName(){
		return $this->belongsTo(Office_models::class, 'model_id', 'id');
	}

	public function office_cost_items(){
        return $this->hasMany(Office_cost_items::class, 'cost_id', 'id');
    }


	public function getCategoryNameAttribute() {
		$category_name = $this->categoryName()->first();
		return ($category_name?$category_name->name:null);
	}

	public function getBrandNameAttribute() {
		$brand_name = $this->brandName()->first();
		return ($brand_name?$brand_name->name:null);
	}

	public function getTypeNameAttribute() {
		$type_name = $this->typeName()->first();
		return ($type_name?$type_name->name:null);
	}

	public function getModelNameAttribute() {
		$model_name = $this->modelName()->first();
		return ($model_name?$model_name->name:null);
	}

	public static function getNextCode() {

		$last_count = 1;

		$cost = Office_costs::select('code')
		->orderBy('code', 'desc')
		->first();

		if($cost) {
			$data = explode("C", $cost->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%05d', $last_count);
		$COUNTER = $curr_count;

		return "C".$COUNTER;
	}
}
