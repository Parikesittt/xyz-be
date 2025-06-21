<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_brands extends Model
{
    protected $table = 'office_brand';
    protected $guarded = [];
    protected $appends = ['category_name'];

    public function categoryName(){
		return $this->belongsTo(Office_categorys::class, 'category_id', 'id');
	}

	public function getCategoryNameAttribute() {
		$category_name = $this->categoryName()->first();
		return ($category_name?$category_name->name:null);
	}

	public static function getNextCode() {

		$last_count = 1;

		$brand = Office_brands::select('code')
		->orderBy('code', 'desc')
		->first();

		if($brand) {
			$data = explode("B", $brand->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%03d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return "B".$COUNTER;
	}
}
