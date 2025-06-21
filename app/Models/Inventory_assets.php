<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory_assets extends Model
{
    protected $guarded = [];
    protected $appends = ['company_code', 'po_internal_id','po_warehouse_id','warehouse_po_name','location_code','warehouse_receive_name','warehouse_name','location_name','warehouse_code','department_name','transaction_type_name','warehouse_send_id','warehouse_send_name','qty_po','unit_po','purchase_order_id','po_code'];

    public function transaction() {
      return $this->belongsTo(Transactions::class, 'transaction_id', 'id');
    }
	
	public function company() {
      return $this->belongsTo(Companys::class);
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
	
	public function transaction_type(){
        return $this->belongsTo(Transaction_types::class, 'transaction_type', 'id');
    }
	
	public function warehouse_send() {
	  return $this->belongsTo(Transactions::class, 'to_id', 'id');
	}
	
	public function warehouse_send_name() {
	  return $this->belongsTo(Warehouses::class, 'warehouse_send_id', 'id');
	}
	
	public function warehouse_receive_name() {
	  return $this->belongsTo(Warehouses::class, 'warehouse_receive_id', 'id');
	}

	public function warehouse_po(){
        return $this->belongsTo(Warehouses::class,'po_warehouse_id','id');
    }
	
	public function purchase_order_internal(){
        return $this->belongsTo(Purchase_orders::class,'po_internal_id','id');
    }
	

	public function getPoWarehouseIdAttribute() {
       $purchase_order_internal = $this->purchase_order_internal()->first();
       return ($purchase_order_internal?$purchase_order_internal->warehouse_id:null);
    }
	
	public function getCompanyCodeAttribute() {
       $company = $this->company()->first();
       return ($company?$company->code:null);
    }
  
    public function getWarehousePoNameAttribute() {
       $warehouse_po = $this->warehouse_po()->first();
       return ($warehouse_po?$warehouse_po->name:null);
    }
	
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	
	public function getPurchaseOrderIdAttribute() {
       $transaction = $this->transaction()->first();
       return ($transaction?$transaction->po_id:null);
    }
	
	public function getPoInternalIdAttribute() {
       $transaction = $this->transaction()->first();
       return ($transaction?$transaction->po_internal_id:null);
    }
	public function getQtyPoAttribute() {
       $purchase_order_detail = $this->purchase_order_detail()->first();
       return ($purchase_order_detail?$purchase_order_detail->quantity:null);
    }
	public function getUnitPoAttribute() {
       $purchase_order_detail = $this->purchase_order_detail()->first();
       return ($purchase_order_detail?$purchase_order_detail->item_unit:null);
    }
	
	public function getPoCodeAttribute() {
       $purchase_order = $this->purchase_order()->first();
	   
	    return ($purchase_order?$purchase_order->number_po:null);
    }
	
	public function getUnitSoAttribute() {
       $sales_order_month = $this->sales_order_month()->first();
       return ($sales_order_month?$sales_order_month->item_unit:null);
    }
	
	public function getWarehouseSendIdAttribute() {
       $warehouse = $this->warehouse_send()->first();
       return ($warehouse?$warehouse->warehouse_receive_id:null);
    }
	
	public function getWarehouseSendNameAttribute() {
       $warehouse = $this->warehouse_send_name()->first();
       return ($warehouse?$warehouse->name:null);
    }
	
	public function getWarehouseReceiveNameAttribute() {
       $warehouse = $this->warehouse_receive_name()->first();
       return ($warehouse?$warehouse->name:null);
    }
	
	public function getTransactionTypeNameAttribute() {
       $transaction_type = $this->transaction_type()->first();
       return ($transaction_type?$transaction_type->name:null);
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
