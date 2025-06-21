<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_models extends Model
{
    protected $table = 'office_model';
    protected $guarded = [];
    protected $appends = ['category_name', 'brand_name', 'type_name'];

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

	public static function getNextCode() {

		$last_count = 1;

		$model = Office_models::select('code')
		->orderBy('code', 'desc')
		->first();

		if($model) {
			$data = explode("M", $model->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%03d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return "M".$COUNTER;
	}
}
