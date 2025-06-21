<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales_orders extends Model
{
    protected $guarded = [];
    protected $appends = ['po_number','po_description','po_location_id','po_warehouse_id','warehouse_name_frozen','warehouse_name_dry','warehouse_receive_name','warehouse_po_name','warehouse_receive_code','attachment_type_name','location_name','location_backcharge','daily_pob_estimated','price_pob_estimated','user_name','warehouse_name','customer_name','customer_code','customer_address','salesman_name', 'customer_terms', 'customer_limit', 'customer_available', 'customer_balance', 'customer_channel', 'cust_type', 'customer_group', 'limit_so', 'limit_invoice', 'customer_phone', 'customer_npwp',  'customer_branch', 'limit_cash', 'customer_invoicegroup'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	public function location(){
        return $this->belongsTo(Locations::class);
    }
	public function warehouse(){
        return $this->belongsTo(Warehouses::class);
    }
	public function warehouse_po(){
        return $this->belongsTo(Warehouses::class,'po_warehouse_id','id');
    }
	public function warehouse_receive(){
        return $this->belongsTo(Warehouses::class,'warehouse_receive_id','id');
    }
	public function warehouse_dry(){
        return $this->belongsTo(Warehouses::class,'warehouse_dry_id','id');
    }
	public function warehouse_frozen(){
        return $this->belongsTo(Warehouses::class,'warehouse_frozen_id','id');
    }
	public function customer(){
        return $this->belongsTo(Customers::class);
    }
	public function salesman(){
        return $this->belongsTo(Salesmans::class,'salesman_id','id');
    }
	public function user(){
        return $this->belongsTo(Users::class);
    }
	public function itemcategorys(){
        return $this->hasMany(Itemcategorys::class, 'sales_order_id', 'id');
    }
	public function subcategorys(){
        return $this->hasMany(Subcategorys::class, 'sales_order_id', 'id');
    }
	public function items(){
        return $this->hasMany(Items::class, 'sales_order_id', 'id');
    }
	public function sales_order_months(){
        return $this->hasMany(Sales_order_months::class, 'sales_order_id', 'id')
		->orderByRaw('sales_order_month.id desc');
    }

    public function sales_order_dist_months(){
        return $this->hasMany(Sales_order_months::class, 'sales_order_id', 'id')
        ->orderByRaw('sales_order_month.id asc');
    }
	
	public function sales_order_exportss(){
        return $this->hasMany(Sales_order_months::class, 'sales_order_id', 'id');
							
    }
	
	public function sales_order_month_regulers(){
        return $this->hasMany(Sales_order_months::class, 'sales_order_id', 'id')
					->where('pr_item_type','=','Reguler');
    }
	public function sales_order_month_handcarrys(){
        return $this->hasMany(Sales_order_months::class, 'sales_order_id', 'id')
					->where('pr_item_type','=','Handcarry');
    }
	
	
	public function item_subcategory(){
        return $this->belongsTo(Item_subcategorys::class);
    }
	
	public function purchase_order(){
        return $this->belongsTo(Purchase_orders::class,'po_id','id');
    }


    public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	
	public function getPoNumberAttribute() {
       $purchase_order = $this->purchase_order()->first();
       return ($purchase_order?$purchase_order->number_po:null);
    }
	public function getPoDescriptionAttribute() {
       $purchase_order = $this->purchase_order()->first();
       return ($purchase_order?$purchase_order->description:null);
    }
	
	public function getPoWarehouseIdAttribute() {
       $purchase_order = $this->purchase_order()->first();
       return ($purchase_order?$purchase_order->warehouse_id:null);
    }
	
	public function getPoLocationIdAttribute() {
       $purchase_order = $this->purchase_order()->first();
       return ($purchase_order?$purchase_order->location_id:null);
    }
	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	public function getWarehouseReceiveNameAttribute() {
       $warehouse_receive = $this->warehouse_receive()->first();
       return ($warehouse_receive?$warehouse_receive->name:null);
    }
	
	public function getWarehousePoNameAttribute() {
       $warehouse_po = $this->warehouse_po()->first();
       return ($warehouse_po?$warehouse_po->name:null);
    }
	public function getWarehouseReceiveCodeAttribute() {
       $warehouse_receive = $this->warehouse_receive()->first();
       return ($warehouse_receive?$warehouse_receive->code:null);
    }
	public function getWarehouseNameDryAttribute() {
       $warehouse = $this->warehouse_dry()->first();
       return ($warehouse?$warehouse->name:null);
    }
	public function getWarehouseNameFrozenAttribute() {
       $warehouse = $this->warehouse_frozen()->first();
       return ($warehouse?$warehouse->name:null);
    }
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	public function getSalesmanNameAttribute() {
       $salesman = $this->salesman()->first();
       return ($salesman?$salesman->name:null);
    }
	public function getCustomerNameAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->name:null);
    }
	
	public function getCustomerCodeAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->accountNum:null);
    }
	public function getCustomerAddressAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->address:null);
    }
  public function getCustomerTermsAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->terms:null);
    }
  public function getCustomerLimitAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->limit:null);
    }
  public function getCustomerAvailableAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->available:null);
    }
  public function getCustomerBalanceAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->balance:null);
    }
  public function getCustomerChannelAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->channel_id:null);
    }
  public function getCustTypeAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->type:null);
    }
  public function getCustomerGroupAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->head_group:null);
    }
  public function getLimitSoAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->limit_so:null);
    }
  public function getLimitInvoiceAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->limit_invoice:null);
    }
  public function getCustomerPhoneAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->phone:null);
    }
  public function getCustomerNpwpAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->npwp:null);
    }
  public function getCustomerBranchAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->branch:null);
    }
  public function getLimitCashAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->limit_cash:null);
    }
  public function getCustomerInvoicegroupAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->invoice_group:null);
    }
	public function getLocationBackchargeAttribute() {
       $locationbackcharge = $this->location()->first();
       return ($locationbackcharge?$locationbackcharge->backcharge:null);
    }
	public function getDailyPobEstimatedAttribute() {
       $daily_pob_estimated = $this->location()->first();
       return ($daily_pob_estimated?$daily_pob_estimated->daily_pob_estimated:null);
    }
	public function getPricePobEstimatedAttribute() {
       $price_pob_estimated = $this->location()->first();
       return ($price_pob_estimated?$price_pob_estimated->price_pob_estimated:null);
    }
	public static function getNextCounterId($location_id) {

      


      $last_count = 1;

      $CODE = 'SO';

	  $F ='F/';
     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $sales_order = Sales_orders::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($sales_order) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $sales_order->code);
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
	  
	  $F ='F/';

     
	  $IPO = '-';
      $TAHUN_BULAN_TANGGAL = date('Ymd');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $sales_order = Sales_orders::select('pr_lokasi')
        ->where('pr_lokasi','like', $CODE.$CODELOCATION.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('pr_lokasi', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($sales_order) {
        $data = explode($CODE.$CODELOCATION.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $sales_order->pr_lokasi);
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
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $sales_order = Sales_orders::select('pr_lokasi')
        ->where('pr_lokasi','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('pr_lokasi', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($sales_order) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $sales_order->pr_lokasi);
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
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN = date('y');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $sales_order = Sales_orders::select('pr_lokasi')
        ->where('pr_lokasi','like', $CODE.$IPO.$TAHUN.$IPO.$F.'%')
        ->orderBy('pr_lokasi', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($sales_order) {
        $data = explode($CODE.$IPO.$TAHUN.$IPO.$F, $sales_order->pr_lokasi);
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
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $sales_order = Sales_orders::select('pr_lokasi')
        ->where('pr_lokasi','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('pr_lokasi', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($sales_order) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $sales_order->pr_lokasi);
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
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $sales_order = Sales_orders::select('pr_lokasi')
        ->where('pr_lokasi','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('pr_lokasi', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($sales_order) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $sales_order->pr_lokasi);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
}
