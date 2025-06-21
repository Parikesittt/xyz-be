<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_cost_onsites extends Model
{
    protected $table = 'office_cost_onsite';
    protected $guarded = [];
    protected $appends = ['area_name'];

    public function office_onsite_area() {
      return $this->belongsTo(Office_onsite_areas::class, 'area_id', 'id');
    }


	public function getAreaNameAttribute() {
       $office_onsite_area = $this->office_onsite_area()->first();
       return ($office_onsite_area?$office_onsite_area->name:null);
    }

	public static function getNextCode() {

		$last_count = 1;

		$cost = Office_cost_onsites::select('code')
		->orderBy('code', 'desc')
		->first();

		if($cost) {
			$data = explode("CON", $cost->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%05d', $last_count);
		$COUNTER = $curr_count;

		return "CON".$COUNTER;
	}
}
