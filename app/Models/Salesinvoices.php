<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salesinvoices extends Model
{
    protected $guarded = [];
    protected $appends = ['customer_name','so_number','pl_number','customer_accountnum','spp_code'];

    public function customer() {
      return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }
	
	public function sales_order() {
      return $this->belongsTo(Sales_orders::class, 'so_id', 'id');
    }
	
	public function transaction() {
      return $this->belongsTo(Transactions::class, 'pl_id', 'id');
    }
	
	public function spp_code() {
	  return $this->belongsTo(Spp_ars::class, 'spp_id', 'id');
	}

    public function getCustomerNameAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->name:null);
    }
	
	public function getCustomerAccountnumAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->accountNum:null);
    }
	
	public function getSoNumberAttribute() {
       $sales_order = $this->sales_order()->first();
       return ($sales_order?$sales_order->code:null);
    }
	public function getPlNumberAttribute() {
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

	  $CODECATEGORY = 'INV_AR';
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  
      // get last count
      $item = Salesinvoices::select('code')
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
	
	public static function getNextCounterCNId($user_company_id) {

      


      $last_count = 1;

	  $CODECATEGORY = 'CN_AR';
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  
      // get last count
      $item = Salesinvoices::select('code')
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
