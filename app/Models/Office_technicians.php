<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_technicians extends Model
{
    protected $table = 'office_technician';
    protected $guarded = [];

    public static function getNextCode() {

		$last_count = 1;

		$technician = Office_technicians::select('code')
		->orderBy('code', 'desc')
		->first();

		if($technician) {
			$data = explode("T", $technician->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%06d', $last_count);
		$COUNTER = $curr_count;

		return "T".$COUNTER;
	}
}
