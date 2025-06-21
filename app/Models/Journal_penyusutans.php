<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal_penyusutans extends Model
{
    protected $guarded = [];
    protected $appends = ['warehouse_name', 'location_name', 'user_name'];

    public function location() {
      return $this->belongsTo(Locations::class);
    }
	
	public function warehouse() {
      return $this->belongsTo(Warehouses::class);
    }
	
	public function journal_penyusutan_items(){
        return $this->hasMany(Journal_penyusutan_items::class, 'journal_penyusutan_id', 'id');
    }	
	
	public function user(){
        return $this->belongsTo(Users::class);
    }
	
	public function item_code() {
	  return $this->belongsTo(Items::class, 'item_code_id', 'id');
	}
	
	
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	
	public static function getNextCounterJPId() {

		$last_count = 1;

		$CODE = 'JP';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$journal_penyusutan = Journal_penyusutans::select('code')
							->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.'%')
							->orderBy('code', 'desc')
							->first();
	  
		if($journal_penyusutan) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO, $journal_penyusutan->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
}
