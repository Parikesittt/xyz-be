<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exchange_adj_banks extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name', 'accountId', 'name'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	public function itemcategorys(){
        return $this->hasMany(Itemcategorys::class, 'itemgroup_id', 'id');
    }
	public function subcategorys(){
        return $this->hasMany(Subcategorys::class, 'itemgroup_id', 'id');
    }
	public function items(){
        return $this->hasMany(Items::class, 'itemgroup_id', 'id');
    }
	public function sales_orders(){
        return $this->hasMany(Sales_orders::class, 'sales_id', 'id');
    }
	public function bankaccounts(){
        return $this->belongsTo(Bankaccounts::class, 'bankaccount_id', 'id');
    }
	
	public function exchange_adj_item_banks(){
        return $this->hasMany(Exchange_adj_item_banks::class, 'exchange_adj_bank_id', 'id');
    }
	
		
	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getAccountIdAttribute() {
       $bankaccounts = $this->bankaccounts()->first();
       return ($bankaccounts?$bankaccounts->accountId:null);
    }
	public function getNameAttribute() {
       $bankaccounts = $this->bankaccounts()->first();
       return ($bankaccounts?$bankaccounts->name:null);
    }
	
	public static function getNextCounterId() {

      /* $app = \Slim\Slim::getInstance();


      $last_count = 1;
	  
      // get last count
      $itemgroup = Itemgroups::select('code')
        ->orderBy('code', 'desc')
        ->first();
	  
	  if($itemgroup) {
        $data = $itemgroup->code;
        $last_count = $data + 1;
      }

      return $last_count; */
	  

      $last_count = 1;

      $CODE = 'REVBANK';

     
	  $IPO = '/';
	  $TAHUN = date('y');
	  $BULAN = date('m');
	  /* $CODELOCATION = $location_id; */
	  
      // get last count
      $exchange_adj_bank = Exchange_adj_banks::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN.$BULAN.$IPO.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($exchange_adj_bank) {
        $data = explode($CODE.$IPO.$TAHUN.$BULAN.$IPO, $exchange_adj_bank->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN.$BULAN.$IPO.$COUNTER;
	  
    }
}
