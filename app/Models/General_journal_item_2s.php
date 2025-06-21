<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class General_journal_item_2s extends Model
{
    protected $guarded = [];
    protected $appends = ['location_name', 'user_name', 'warehouse_name'];

    public function location() {
      return $this->belongsTo(Locations::class);
    }
	
	public function warehouse() {
      return $this->belongsTo(Warehouses::class);
    }
	
	public function user() {
      return $this->belongsTo(Users::class);
    }
		
	public function general_journal_2s() {
      return $this->belongsTo(General_journal_2s::class, 'general_journal_2_id', 'id');
    }

    public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
		
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	
	public static function getNextCounterVoucherId($code) {

      $last_count = 1;

	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	   //$CODELOCATION = $location_id;
	  
      // get last count
      $voucher = General_journal_item_2s::select('voucher')
        ->where('voucher','like', '%'.$TAHUN_BULAN_TANGGAL.$IPO.$code)
        ->orderBy('voucher', 'desc')
        ->first();


      if($voucher) {
        $data = explode($IPO.$TAHUN_BULAN_TANGGAL.$IPO.$code, $voucher->voucher);
        $last_count = intval($data[0]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $COUNTER.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$code;
    }
}
