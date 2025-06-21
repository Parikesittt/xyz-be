<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facilitys extends Model
{
    protected $table = 'facility';
    protected $guarded = [];

    public static function getNextCounterId() {

		$last_count = 1;

		$CODE = 'F';

		// get last count
		$facility = Facilitys::select('code')
			->where('code','like', $CODE.'%')
			->orderBy('code', 'desc')
			->first();

		if($facility) {
			$data = explode($CODE, $facility->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%03d', $last_count);
		$COUNTER = $curr_count;

		return $CODE.$COUNTER;
    }
}
