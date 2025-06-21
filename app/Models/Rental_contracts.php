<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental_contracts extends Model
{
    protected $guarded = [];
    protected $appends = ['user_name'];

    public function user() {
      return $this->belongsTo(Users::class);
    }
	
	public function rental_contract_items(){
        return $this->hasMany(Rental_contract_items::class, 'rental_contract_id', 'id')
					 ->select()
					 ->orderByRaw('id asc');
    }		
	
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	
	public static function getNextCounterId() {

		


		$last_count = 1;

		$CODE = 'RC';
		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('ym');
	  
		// get last count
		$rental_contract = Rental_contracts::select('code')
			->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.'%')
			->orderBy('code', 'desc')
			->first();

		if($rental_contract) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO, $rental_contract->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
}
