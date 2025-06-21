<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $guarded = [];
    protected $appends = ['supplier_name','po_number','rr_number','supplier_accountnum','sales_tax_group','spp_code'];

    public function supplier() {
      return $this->belongsTo(Suppliers::class, 'supplier_id', 'id');
    }
	
	public function purchase_order() {
      return $this->belongsTo(Purchase_orders::class, 'po_id', 'id');
    }
	
	public function transaction() {
      return $this->belongsTo(Transactions::class, 'rr_id', 'id');
    }
	
	public function sales_tax_groups(){
        return $this->hasMany(Sales_tax_groups::class, 'id', 'ppn_type');
    }
	
	public function spp_code() {
	  return $this->belongsTo(Spps::class, 'spp_id', 'id');
	}

	public function getSupplierNameAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->name:null);
    }
	
	public function getSupplierAccountnumAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->accountNum:null);
    }
	
	public function getPoNumberAttribute() {
       $purchase_order = $this->purchase_order()->first();
       return ($purchase_order?$purchase_order->number_po:null);
    }
	public function getRrNumberAttribute() {
       $transaction = $this->transaction()->first();
       return ($transaction?$transaction->code:null);
    }
	public function getSalesTaxGroupAttribute() {
       $sales_tax_groups = $this->sales_tax_groups()->first();
       return ($sales_tax_groups?$sales_tax_groups->sales_tax_group:null);
    }
	
	public function getSppCodeAttribute() {
		$spp = $this->spp_code()->first();
		return ($spp?$spp->code:null);
	}
	
	public static function getNextCounterId($user_company_id) {

      $last_count = 1;

	  $CODECATEGORY = 'INV_AP';
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  
      // get last count
      $item = Invoices::select('code')
        ->where('code','like', $CODECATEGORY.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.'%')
	 ->where('company_id','=', $user_company_id)	
        ->orderBy('code', 'desc')
        ->first();
	  
	  if($item) {
        $data = explode($CODECATEGORY.$IPO.$TAHUN_BULAN_TANGGAL.$IPO, $item->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODECATEGORY.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
}
