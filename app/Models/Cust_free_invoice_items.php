<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cust_free_invoice_items extends Model
{
    protected $guarded = [];
    protected $appends = ['location_name', 'user_name' ,'warehouse_name'];

    public function location() {
      return $this->belongsTo(Locations::class);
    }
	
	public function warehouse() {
      return $this->belongsTo(Warehouses::class);
    }
	
	public function user() {
      return $this->belongsTo(Users::class);
    }
	
	public function cust_free_invoices(){
        return $this->hasMany(Cust_free_invoices::class, 'cust_free_invoice_id', 'id');
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
	
	public static function getNextCounterId() {

      /*During the registration, the customer number ID is generated automatically by the system 
      (format: C_TAHUN_BULAN_TANGGAL_COUNTER. Example: C201403100001).*/

      $last_count = 1;
      $IPTN= 'J';
	   $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');

      // get last count
      $general_journal = General_journals::select('code')
        ->where('code','like', $IPTN.$TAHUN_BULAN_TANGGAL.$IPO.'%')
        ->orderBy('code', 'desc')
        ->first();

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($general_journal) {
        $data = explode($IPTN.$TAHUN_BULAN_TANGGAL.$IPO, $general_journal->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $IPTN.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
}
