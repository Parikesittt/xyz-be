<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory_temps extends Model
{
    protected $guarded = [];
    protected $appends = ['so_code','customers_id','customer_name','location_code','warehouse_name','location_name','warehouse_code','department_name','supplier_id','supplier_name','pr_code','transaction_type_name','warehouse_send_id','warehouse_send_name','qty_po','unit_po'];

    public function item() {
      return $this->belongsTo(Items::class);
    }
  
	public function supplier() {
    	return $this->belongsTo(Suppliers::class);
    }
	
	public function items(){
        return $this->hasMany(Items::class, 'item_code', 'code');
    }
	
	public function unit_converts(){
        return $this->hasMany(Unit_Converts::class, 'item_code', 'item_code');
    }
	
	public function location(){
        return $this->belongsTo(Locations::class);
    }

	public function warehouse() {
      return $this->belongsTo(Warehouses::class);
    }
	
	public function department() {
	  return $this->belongsTo(Locations::class, 'department_id', 'id');
	}
	
	public function purchase_order(){
        return $this->belongsTo(Purchase_orders::class, 'po_id', 'id');
    }
	
	public function purchase_order_detail(){
        return $this->belongsTo(Purchase_order_details::class, 'po_detail_id', 'id');
    }
	
	public function purchase_request(){
        return $this->belongsTo(Purchase_requests::class, 'pr_id', 'id');
    }
	
	public function sales_order(){
        return $this->belongsTo(Sales_orders::class, 'so_id', 'id');
    }
	
	public function transaction_type(){
        return $this->belongsTo(Transaction_types::class, 'transaction_type', 'id');
    }
	
	public function warehouse_send() {
	  return $this->belongsTo(Transactions::class, 'to_id', 'id');
	}
	
	public function warehouse_send_name() {
	  return $this->belongsTo(Warehouses::class, 'warehouse_send_id', 'id');
	}
	
	public function customer() {
	  return $this->belongsTo(Customers::class, 'customers_id', 'id');
	}

   
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	public function getSoCodeAttribute() {
       $sales_order = $this->sales_order()->first();
       return ($sales_order?$sales_order->code:null);
    }
	public function getCustomerNameAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->name:null);
    }
	
	public function getCustomersIdAttribute() {
       $sales_order = $this->sales_order()->first();
       return ($sales_order?$sales_order->customer_id:null);
    }
	
	public function getQtyPoAttribute() {
       $purchase_order_detail = $this->purchase_order_detail()->first();
       return ($purchase_order_detail?$purchase_order_detail->quantity:null);
    }
	
	public function getUnitPoAttribute() {
       $purchase_order_detail = $this->purchase_order_detail()->first();
       return ($purchase_order_detail?$purchase_order_detail->item_unit:null);
    }
	
	public function getWarehouseSendIdAttribute() {
       $warehouse = $this->warehouse_send()->first();
       return ($warehouse?$warehouse->warehouse_receive_id:null);
    }
	
	public function getWarehouseSendNameAttribute() {
       $warehouse = $this->warehouse_send_name()->first();
       return ($warehouse?$warehouse->name:null);
    }
	
	public function getTransactionTypeNameAttribute() {
       $transaction_type = $this->transaction_type()->first();
       return ($transaction_type?$transaction_type->name:null);
    }
 
    public function getSupplierIdAttribute() {
       $purchase_order = $this->purchase_order()->first();
	   
	    return ($purchase_order?$purchase_order->supplier_id:null);
    }
	
	public function getPrCodeAttribute() {
       $purchase_request = $this->purchase_request()->first();
	   
	    return ($purchase_request?$purchase_request->pr_lokasi:null);
    }
	
	public function getSupplierNameAttribute() {
       $supplier = $this->supplier()->first();
	   
	    return ($supplier?$supplier->name:null);
    }
  
    public function getDepartmentNameAttribute() {
       $department = $this->department()->first();
       return ($department?$department->name:null);
    }
	
    public function getWarehouseCodeAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->code:null);
    }
	
    public function getItemGroupNumberAttribute() {
       $groupname = $this->item()->first();
       return ($groupname?$groupname->itemgroupnumber:null);
    }
	
    public function getLocationCodeAttribute() {
		 $location = $this->location()->first();
		return ($location?$location->code:null);
	}

	public function getLocationNameAttribute() {
		 $location = $this->location()->first();
		return ($location?$location->name:null);
	}
}
