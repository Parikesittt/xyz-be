<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exchange_adj_aps extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name', 'accountNum', 'name'];

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
    public function suppliers(){
        return $this->belongsTo(Suppliers::class, 'supplier_id', 'id');
    }
	
	public function exchange_adj_item_aps(){
        return $this->hasMany(Exchange_adj_item_aps::class, 'exchange_adj_ap_id', 'id');
    }
	
	
	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getAccountNumAttribute() {
       $suppliers = $this->suppliers()->first();
       return ($suppliers?$suppliers->accountNum:null);
    }
	public function getNameAttribute() {
       $suppliers = $this->suppliers()->first();
       return ($suppliers?$suppliers->name:null);
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

      $CODE = 'REVAP';

     
	  $IPO = '/';
	  $TAHUN = date('y');
	  $BULAN = date('m');
	  /* $CODELOCATION = $location_id; */
	  
      // get last count
      $exchange_adj_ap = Exchange_adj_aps::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN.$BULAN.$IPO.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($exchange_adj_ap) {
        $data = explode($CODE.$IPO.$TAHUN.$BULAN.$IPO, $exchange_adj_ap->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN.$BULAN.$IPO.$COUNTER;
	  
    }
}
