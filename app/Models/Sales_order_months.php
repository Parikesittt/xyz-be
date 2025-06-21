<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales_order_months extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name','location_name','supplier_name','itemcategory_name', 'warehouse_name', 'customer_name','product_code'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	public function itemcategory() {
      return $this->belongsTo(Itemcategorys::class);
    }
	public function location(){
        return $this->belongsTo(Locations::class);
    }
	
	public function warehouse(){
        return $this->belongsTo(Warehouses::class);
    }
	public function supplier(){
        return $this->belongsTo(Suppliers::class);
    }
	
	public function customer(){
        return $this->belongsTo(Customers::class);
    }
	public function itemcategorys(){
        return $this->hasMany(Itemcategorys::class, 'purchase_request_id', 'id');
    }
	public function subcategorys(){
        return $this->hasMany(Subcategorys::class, 'purchase_request_id', 'id');
    }
	public function items(){
        return $this->hasMany(Items::class, 'id', 'item_code');
    }
	public function purchase_request() {
      return $this->belongsTo(Purchase_requests::class, 'purchase_request_id', 'id');
    }


    public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getItemcategoryNameAttribute() {
       $itemcategory = $this->itemcategory()->first();
       return ($itemcategory?$itemcategory->name:null);
    }
	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	public function getSupplierNameAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->name:null);
    }
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	public function getProductCodeAttribute() {
       $items = $this->items()->first();
       return ($items?$items->product_code:null);
    }
	public function getCustomerNameAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->name:null);
    }
	
	public static function getNextCounterId($location_id) {

      


      $last_count = 1;

      $CODE = 'PR';

     $F ='F/';

      $TAHUN_BULAN_TANGGAL = date('Ym');

      // get last count
      $purchase_request = Sales_order_months::select('location_id')
        ->where('location_id','like', $TAHUN_BULAN_TANGGAL.$F.'%')
        ->orderBy('location_id', 'desc')
        ->first();
	  
	  $CODELOCATION = $location_id;

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($purchase_request) {
        $data = explode($TAHUN_BULAN_TANGGAL.$F, $purchase_request->location_id);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE . $CODELOCATION.'-'. $TAHUN_BULAN_TANGGAL .'-'.$F. $COUNTER;
    }
}
