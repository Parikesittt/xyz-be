<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_categorys extends Model
{
    protected $table = 'office_category';
    protected $guarded = [];

    public static function getNextCode() {

		$last_count = 1;

		$category = Office_categorys::select('code')
		->orderBy('code', 'desc')
		->first();

		if($category) {
			$data = explode("C", $category->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%03d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return "C".$COUNTER;
	}
}
