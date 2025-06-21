<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cust_free_invoices extends Model
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
	
	public function cust_free_invoice_items(){
        return $this->hasMany(Cust_free_invoice_items::class, 'cust_free_invoice_id', 'id');
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
	
	public static function getNextCounterId($user_company_id,$z) {
		
		/*During the registration, the customer number ID is generated automatically by the system 
		(format: C_TAHUN_BULAN_TANGGAL_COUNTER. Example: C201403100001).*/
		
		$last_count = 1;
		$IPTN= 'FT_AR';
		$IPO = '/';
		//$TAHUN_BULAN_TANGGAL = date('ym');
		
		// get last count
		$cust_free_invoice = Cust_free_invoices::select('code')
			->where('code','like', $IPTN.$IPO.$z.$IPO.'%')
			->orderBy('code', 'desc')
			->first();
		
        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();
		
		if($cust_free_invoice) {
			$data = explode($IPTN.$IPO.$z.$IPO, $cust_free_invoice->code);
			//var_dump($data);
			$last_count = intval($data[1]) + 1;
		}
		
		$curr_count = '';
		$curr_count = sprintf('%04d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;
		
		return $IPTN.$IPO.$z.$IPO.$COUNTER;
    }
	
	public static function getNextCounterKwt($user_company_id,$z) {
		
		/*During the registration, the customer number ID is generated automatically by the system 
		(format: C_TAHUN_BULAN_TANGGAL_COUNTER. Example: C201403100001).*/
		
		$last_count = 1;
		$IPTN= 'KWT';
		$IPO = '/';
		//$TAHUN_BULAN_TANGGAL = date('ym');
		
		// get last count
		$cust_free_invoice = Cust_free_invoices::select('code')
			->where('code','like', $IPTN.$IPO.$z.$IPO.'%')
			->orderBy('code', 'desc')
			->first();
		
        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();
		
		if($cust_free_invoice) {
			$data = explode($IPTN.$IPO.$z.$IPO, $cust_free_invoice->code);
			//var_dump($data);
			$last_count = intval($data[1]) + 1;
		}
		
		$curr_count = '';
		$curr_count = sprintf('%04d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;
		
		return $IPTN.$IPO.$z.$IPO.$COUNTER;
    }
	
	public static function getNextCounterCNId($user_company_id,$z) {
		
		/*During the registration, the customer number ID is generated automatically by the system 
		(format: C_TAHUN_BULAN_TANGGAL_COUNTER. Example: C201403100001).*/
		
		$last_count = 1;
		$IPTN= 'CN';
		$IPO = '/';
		//$TAHUN_BULAN_TANGGAL = date('ym');
		
		// get last count
		$cust_free_invoice = Cust_free_invoices::select('code')
			->where('company_id', '=', $user_company_id)
			->where('code','like', $IPTN.$IPO.$z.$IPO.'%')
			->orderBy('code', 'desc')
			->first();
		
        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();
		
		if($cust_free_invoice) {
			$data = explode($IPTN.$IPO.$z.$IPO, $cust_free_invoice->code);
			$last_count = intval($data[1]) + 1;
		}
		
		$curr_count = '';
		$curr_count = sprintf('%04d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;
		
		return $IPTN.$IPO.$z.$IPO.$COUNTER;
    }
	
	public static function getNextCounterMultiId($user_company_id,$z) {
		
		$last_count = 1;
		$IPTN= 'FT_ARB';
		$IPO = '/';
		
		// get last count
		$multiple_print = Multiple_prints::select('code')
			->where('code','like', $IPTN.$IPO.$z.$IPO.'%')
			->orderBy('code', 'desc')
			->first();
		
		if($multiple_print) {
			$data = explode($IPTN.$IPO.$z.$IPO, $multiple_print->code);
			//var_dump($data);
			$last_count = intval($data[1]) + 1;
		}
		
		$curr_count = '';
		$curr_count = sprintf('%04d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;
		
		return $IPTN.$IPO.$z.$IPO.$COUNTER;
    }
}
