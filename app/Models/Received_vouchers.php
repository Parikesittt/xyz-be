<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Received_vouchers extends Model
{
    protected $guarded = [];
    protected $appends = ['customer_name', 'salesinvoice_code'];

    public function received_voucher_items() {
	  return $this->hasMany(Received_voucher_items::class, 'received_voucher_id', 'id')
	  ->select('received_voucher_item.*', 'salesinvoice.grand_total', 'salesinvoice.total_ppn', 'salesinvoice.discon', 'salesinvoice.rv_price',  'salesinvoice.rv_price as amount_shadow')
			->leftJoin('salesinvoice', function ($join){
				$join->on('received_voucher_item.salesinvoice_id','=','salesinvoice.id')
					  ->on( 'received_voucher_item.received_voucher_id','=','salesinvoice.rv_id');
				
				})
			->orderByRaw('received_voucher_item.id desc');
    }
	
    public function customer() {
      return $this->belongsTo(Customers::class);
    }
	
	public function invoice_items(){
        return $this->hasMany(Invoices::class, 'spp_id', 'id');
    }
	
	public function salesinvoice() {
      return $this->belongsTo(Salesinvoices::class);
    }
	
	
	public function getCustomerNameAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->name:null);
    }
	
	public function getSalesinvoiceCodeAttribute() {
       $salesinvoice = $this->salesinvoice()->first();
       return ($salesinvoice?$salesinvoice->code:null);
    }
	
		
	
	 public static function getNextCounterId() {

      $last_count = 1;

      $CODE = 'J';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	   //$CODELOCATION = $location_id;
	  
      // get last count
      $voucher = Received_vouchers::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$IPO.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($voucher) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$IPO, $voucher->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
	
	public static function getNextCounterVoucherId($bank_account) {

      $last_count = 1;

      //$CODE = 'J';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	   //$CODELOCATION = $location_id;
	  
      // get last count
      $cdv = Cdvs::select('voucher')
        ->where('voucher','like', '%'.$TAHUN_BULAN_TANGGAL.$IPO.$bank_account)
        ->orderBy('voucher', 'desc')
        ->first();

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($cdv) {
        $data = explode($IPO.$TAHUN_BULAN_TANGGAL.$IPO.$bank_account, $cdv->voucher);
        $last_count = intval($data[0]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $COUNTER.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$bank_account;
    }
}
