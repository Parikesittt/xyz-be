<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cdvs extends Model
{
    protected $fillable = [
        'id',
        'company_id',
        'code',
        'voucher',
        'supplier_id',
        'bankaccount_id',
        'backaccount_accountId',
        'bankaccount_name',
        'description',
        'date_required',
        'is_posting',
        'user_id',
        'user_posting',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $appends = ['supplier_name', 'supplier_accountNum'];

    public function cdv_items() {
		return $this->hasMany(Cdv_items::class, 'cdv_id', 'id')
			->select('cdv_item.*', 'invoice.grand_total', 'invoice.ppn', 'invoice.discount', 'invoice.cdv_price',  'invoice.cdv_price as amount_shadow')
			->leftJoin('invoice', function ($join){
				$join->on('cdv_item.invoice_id','=','invoice.id')
					  ->on( 'cdv_item.cdv_id','=','invoice.cdv_id');
				
				})
			->orderByRaw('cdv_item.id desc');
    }
	
    public function supplier() {
      return $this->belongsTo(Suppliers::class);
    }
	
	public function invoice_items(){
        return $this->hasMany(Invoices::class, 'spp_id', 'id');
    }
	
		
	public function getSupplierNameAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->name:null);
    }
	
	public function getSupplierAccountNumAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->accountNum:null);
    }
	
	
	 public static function getNextCounterId() {

      $last_count = 1;

      $CODE = 'J';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	   //$CODELOCATION = $location_id;
	  
      // get last count
      $cdv = Cdvs::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$IPO.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($cdv) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$IPO, $cdv->code);
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
