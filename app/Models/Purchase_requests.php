<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase_requests extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name','location_name','location_code','daily_pob_estimated','price_pob_estimated1','user_name','warehouse_name', 'procurement_name','approval_name','campbosh_name'];

    public function atttachment_type() {
		return $this->belongsTo(Attachment_types::class);
	}
	
	public function procurement() {
		return $this->belongsTo(Users::class, 'consolidation_user_id', 'id');
	}
	
	public function campbosh() {
		return $this->belongsTo(Users::class, 'campbosh_id', 'id');
	}
	
	public function approval() {
		return $this->belongsTo(Users::class, 'approval_whs', 'id');
	}
	
	public function location(){
        return $this->belongsTo(Locations::class);
    }
	public function warehouse(){
        return $this->belongsTo(Warehouses::class);
    }
	public function user(){
        return $this->belongsTo(Users::class);
    }
	public function itemcategorys(){
        return $this->hasMany(Itemcategorys::class, 'purchase_request_id', 'id');
    }
	public function subcategorys(){
        return $this->hasMany(Subcategorys::class, 'purchase_request_id', 'id');
    }
	public function items(){
        return $this->hasMany(Items::class, 'purchase_request_id', 'id');
    }
	public function purchase_request_months(){
        return $this->hasMany(Purchase_request_months::class, 'purchase_request_id', 'id');
    }
	
	public function purchase_request_consolidations(){
        return $this->hasMany(Purchase_request_consolidations::class, 'purchase_request_month.purchase_request_id', 'purchase_request.id');				
    }
	
	public function purchase_request_exportss(){
        return $this->hasMany(Purchase_request_months::class, 'purchase_request_id', 'id');				
    }
	
	public function purchase_request_month_regulers(){
        return $this->hasMany(Purchase_request_months::class, 'purchase_request_id', 'id')
					->where('pr_item_type','=','Reguler');
    }
	public function purchase_request_month_handcarrys(){
        return $this->hasMany(Purchase_request_months::class, 'purchase_request_id', 'id')
					->where('pr_item_type','=','Handcarry');
    }
	
	public function item_subcategory(){
        return $this->belongsTo(Item_subcategorys::class);
    }


	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	
	public function getProcurementNameAttribute() {
       $user = $this->procurement()->first();
       return ($user?$user->name:null);
    }
	
	public function getCampboshNameAttribute() {
       $user = $this->campbosh()->first();
       return ($user?$user->name:null);
    }
	
	public function getApprovalNameAttribute() {
       $user = $this->approval()->first();
       return ($user?$user->name:null);
    }
	
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	public function getLocationCodeAttribute() {
       $location = $this->location()->first();
       return ($location?$location->code:null);
    }
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	public function getLocationBackchargeAttribute() {
       $locationbackcharge = $this->location()->first();
       return ($locationbackcharge?$locationbackcharge->backcharge:null);
    }
	public function getDailyPobEstimatedAttribute() {
       $daily_pob_estimated = $this->location()->first();
       return ($daily_pob_estimated?$daily_pob_estimated->daily_pob_estimated:null);
    }
	public function getPricePobEstimated1Attribute() {
       $price_pob_estimated1 = $this->location()->first();
       return ($price_pob_estimated1?$price_pob_estimated1->price_pob_estimated:null);
    }
	public static function getNextCounterId($location_id) {

      


      $last_count = 1;

      $CODE = 'PRL';

     
	  $IPO = '/';
	  $F = 'F/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $purchase_request = Purchase_requests::select('pr_lokasi')
        ->where('pr_lokasi','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('pr_lokasi', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_request) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $purchase_request->pr_lokasi);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCodeId($location_id) {

      


      $last_count = 1;

      $CODE = 'PRC';
	  
	   $F = 'F/';

     
	  $IPO = '-';
      $TAHUN_BULAN_TANGGAL = date('Ymd');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $purchase_request = Purchase_requests::select('pr_lokasi')
        ->where('pr_lokasi','like', $CODE.$CODELOCATION.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('pr_lokasi', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_request) {
        $data = explode($CODE.$CODELOCATION.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $purchase_request->pr_lokasi);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$CODELOCATION.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterIdBO($location_id) {

      


      $last_count = 1;

      $CODE = 'PR';
	  
	   $F = 'F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $purchase_request = Purchase_requests::select('pr_lokasi')
        ->where('pr_lokasi','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('pr_lokasi', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_request) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $purchase_request->pr_lokasi);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	
	public static function getNextCodeNonConsId($location_id) {

      


      $last_count = 1;

      $CODE = 'PR';
	  
	   $F = 'F/';

     
	  $IPO = '/';
      $TAHUN = date('y');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $purchase_request = Purchase_requests::select('pr_lokasi')
        ->where('pr_lokasi','like', $CODE.$IPO.$TAHUN.$IPO.$F.'%')
        ->orderBy('pr_lokasi', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_request) {
        $data = explode($CODE.$IPO.$TAHUN.$IPO.$F, $purchase_request->pr_lokasi);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN.$IPO.$F.$COUNTER;
    }

	public static function getNextCounterIdPRW($location_id) {

      


      $last_count = 1;

      $CODE = 'PR';
	  
	   $F = 'F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $purchase_request = Purchase_requests::select('pr_lokasi')
        ->where('pr_lokasi','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('pr_lokasi', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_request) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $purchase_request->pr_lokasi);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCodeBackcharge($location_id) {

      


      $last_count = 1;

      $CODE = 'PRL';
	  
	   $F = 'F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $purchase_request = Purchase_requests::select('pr_lokasi')
        ->where('pr_lokasi','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('pr_lokasi', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_request) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $purchase_request->pr_lokasi);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
}
