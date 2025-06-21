<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase_orders extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name','location_name','location_backcharge','daily_pob_estimated','price_pob_estimated','user_name','warehouse_name','supplier_name','supplier_accountNum','supplier_vend','supplier_address','purchase_order_code','warehouse_code','supplier_code','location_code','warehouse_to_code','warehousetoname'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	public function location(){
        return $this->belongsTo(Locations::class);
    }
	public function warehouse(){
        return $this->belongsTo(Warehouses::class);
    }
	
	public function warehouse_to(){
        return $this->belongsTo(Warehouses::class,'warehouse_to_id','id');
    }
	
	public function supplier(){
        return $this->belongsTo(Suppliers::class);
    }
	public function user(){
        return $this->belongsTo(Users::class);
    }

	public function purchase_order(){
        return $this->hasMany(Purchase_orders::class, 'po_internal_id', 'id');
    }
	
	public function purchase_order_sos(){
        return $this->hasMany(Purchase_order_details::class, 'purchase_order_id', 'id');
    }
			
	public function purchase_order_details(){
        return $this->hasMany(Purchase_order_details::class, 'purchase_order_id', 'id')
					->select('purchase_order_detail.*','purchase_request_month.qty as qty', 'purchase_request_month.qty_po as qty_po', 'purchase_order_detail.quantity as qty_mirror', 'purchase_order_detail.item_unit as unit_mirror', 'purchase_request_month.item_unit as unit', 'purchase_request.id as id_pr')
				->leftJoin('purchase_request_month', 'purchase_request_month.id','=','purchase_order_detail.purchase_request_month_id')	
				->join('purchase_request', 'purchase_request.pr_lokasi','=','purchase_order_detail.pr_lokasi')
				->where(function($query)
					{
					$query->where('purchase_order_detail.is_substitution',null)
						  ->orWhere('purchase_order_detail.is_substitution',0);
					})
				->where('purchase_order_detail.id_substitution',null)
				->groupBy('purchase_order_detail.purchase_request_month_id');
    }
	
	public function purchase_order_detail_consolidations(){
        return $this->hasMany(Purchase_order_details::class, 'purchase_order_id', 'id')
					->select('purchase_order_detail.*','purchase_request_month.qty as qty', 'purchase_request_month.qty_po as qty_po', 'purchase_order_detail.quantity as qty_mirror', 'purchase_order_detail.item_unit as unit_mirror', 'purchase_request_month.item_unit as unit', 'purchase_request.id as id_pr',
					'a.qty_receive as receive_lok')
				->leftJoin('purchase_request_month', 'purchase_request_month.id','=','purchase_order_detail.purchase_request_month_id')	
				->join('purchase_request', 'purchase_request.pr_lokasi','=','purchase_order_detail.pr_lokasi')
				->leftJoin('purchase_order_detail as a', 'purchase_order_detail.id','=','a.po_detail_internal_id')
				->where(function($query)
					{
					$query->where('purchase_order_detail.is_substitution',null)
						  ->orWhere('purchase_order_detail.is_substitution',0);
					})
				->where('purchase_order_detail.id_substitution',null)
				->groupBy('purchase_order_detail.purchase_request_month_id');
    }
	
	public function purchase_order_so_regulers(){
        return $this->hasMany(Purchase_order_details::class, 'purchase_order_id', 'id')
					->select('purchase_order_detail.*','sales_order_month.qty as qty', 'sales_order_month.quantity_po_non as quantity_po_non', 'purchase_order_detail.quantity as qty_mirror')
				->leftJoin('sales_order_month', 'sales_order_month.id','=','purchase_order_detail.so_detail_id')	
				->groupBy('purchase_order_detail.so_detail_id');
    }
	public function purchase_order_substitutions(){
        return $this->hasMany(Purchase_order_details::class, 'purchase_order_id', 'id')
				->select()
				->whereNotNull('purchase_order_detail.id_substitution');
    }

    
	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getPurchaseOrderCodeAttribute() {
       $purchase_order = $this->purchase_order()->first();
       return ($purchase_order?$purchase_order->number_po:null);
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
	public function getWarehouseCodeAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->code:null);
    }
	public function getWarehousetonameAttribute() {
       $warehouse_to = $this->warehouse_to()->first();
       return ($warehouse_to?$warehouse_to->name:null);
    }
	public function getWarehouseToCodeAttribute() {
       $warehouse_to = $this->warehouse_to()->first();
       return ($warehouse_to?$warehouse_to->code:null);
    }
	public function getSupplierNameAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->name:null);
    }
	public function getSupplierCodeAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->accountNum:null);
    }
	
	public function getSupplierAccountNumAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->accountNum:null);
    }
	public function getSupplierVendAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->vend_group:null);
    }
	public function getSupplierAddressAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->address:null);
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
	
	public static function getNextCounterId($code) {

      


      $last_count = 1;

      $CODE = $code;

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  
      // get last count
      $purchase_order = Purchase_orders::select('number_po')
        ->where('number_po','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.'%')
        ->orderBy('number_po', 'desc')
        ->first();

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_order) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO, $purchase_order->number_po);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
	
	public static function getNextBarcodeCounterId($code) {

      

      $last_count = 1;

      $CODE = $code;
      $TAHUN_BULAN_TANGGAL = date('y');
	  
      // get last count
      $purchase_order = Purchase_orders::select('barcode')
        ->where('barcode','like', $CODE.$TAHUN_BULAN_TANGGAL.'%')
        ->orderBy('barcode', 'desc')
        ->first();
		
        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_order) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL, $purchase_order->barcode);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$COUNTER;
    }
	
	public static function getNextPOSOCounterId($code) {

      


      $last_count = 1;

      $CODE = $code;

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  
      // get last count
      $purchase_order = Purchase_orders::select('number_po')
        ->where('number_po','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.'%')
        ->orderBy('number_po', 'desc')
        ->first();

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_order) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO, $purchase_order->number_po);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
	
	public static function getNextBarcodePOSOCounterId($code) {

      

      $last_count = 1;

      $CODE = $code;
      $TAHUN_BULAN_TANGGAL = date('y');
	  
      // get last count
      $purchase_order = Purchase_orders::select('barcode')
        ->where('barcode','like', $CODE.$TAHUN_BULAN_TANGGAL.'%')
        ->orderBy('barcode', 'desc')
        ->first();
		
        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_order) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL, $purchase_order->barcode);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$COUNTER;
    }
	
	public static function getNextPOCashCounterId() {

      


      $last_count = 1;

      $CODE = 'POC';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  
      // get last count
      $purchase_order = Purchase_orders::select('number_po')
        ->where('number_po','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.'%')
        ->orderBy('number_po', 'desc')
        ->first();


        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_order) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO, $purchase_order->number_po);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
	
	public static function getNextBarcodePOCashCounterId() {

      

      $last_count = 1;

      $CODE = 'POC';
      $TAHUN_BULAN_TANGGAL = date('y');
	  
      // get last count
      $purchase_order = Purchase_orders::select('barcode')
        ->where('barcode','like', $CODE.$TAHUN_BULAN_TANGGAL.'%')
        ->orderBy('barcode', 'desc')
        ->first();
		
        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_order) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL, $purchase_order->barcode);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$COUNTER;
    }
}
