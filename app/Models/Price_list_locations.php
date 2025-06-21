<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price_list_locations extends Model
{
    protected $guarded = [];
    protected $appends = ['location_name','supplier_name','validate_date_from_name','validate_date_to_name','location_code','supplier_name'];

    public function location() {
      return $this->belongsTo(Locations::class);
    }
	public function supplier() {
      return $this->belongsTo(Suppliers::class);
    }
	public function price_list_vendors(){
        return $this->hasMany(Price_list_vendors::class, 'price_list_id', 'id');
    }

    public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	 public function getSupplierNameAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->name:null);
    }
	 public function getValidateDateFromNameAttribute() {
       $validate_date_from = $this->supplier()->first();
       return ($validate_date_from?$validate_date_from->validate_date_from:null);
    }
	 public function getValidateDateToNameAttribute() {
       $validate_date_to = $this->supplier()->first();
       return ($validate_date_to?$validate_date_to->validate_date_to:null);
    }
	public function getLocationCodeAttribute() {
       $location_code = $this->location()->first();
       return ($location_code?$location_code->code:null);
    }

	public static function getNextCounterId() {

      

      /*During the registration, the customer number ID is generated automatically by the system 
      (format: C_TAHUN_BULAN_TANGGAL_COUNTER. Example: C201403100001).*/

      $last_count = 1;
      $MC= 'PLV';
      $TAHUN_BULAN_TANGGAL = date('Ymd');

      // get last count
      $price_location = Price_locations::select('code')
        ->where('code','like', $MC.$TAHUN_BULAN_TANGGAL.'%')
        ->orderBy('code', 'desc')
        ->first();

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($price_location) {
        $data = explode($MC.$TAHUN_BULAN_TANGGAL, $price_location->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $MC.$TAHUN_BULAN_TANGGAL . $COUNTER;
    }
}
