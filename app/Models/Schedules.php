<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    protected $guarded = [];

    public function schedule_items(){
        return $this->hasMany(Schedule_items::class, 'schedule_id', 'id');
    }

    public static function getNextCounterId($company_id) {

		$last_count = 1;

		$CODE = 'MRS';
     
		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		$CODELOCATION = $company_id;
	  
		// get last count
		$schedule = Schedules::select('code')
			->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.'%')
			->orderBy('code', 'desc')
			->first();
	 
		if($schedule) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO, $schedule->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
}
