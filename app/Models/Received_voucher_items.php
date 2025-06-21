<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Received_voucher_items extends Model
{
    protected $guarded = [];
    protected $appends = ['customer_name', 'pl_number'];

    public function upload_files() {
      return $this->hasMany(Upload_files::class, 'upload_file_id', 'id');
    }
	
    public function customer() {
      return $this->belongsTo(Customers::class);
    }
	
	public function transaction() {
      return $this->belongsTo(Transactions::class, 'pl_id', 'id');

    }
	
	public function getCustomerNameAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->name:null);
    }
	
	
	public function getPlNumberAttribute() {
       $transaction = $this->transaction()->first();
       return ($transaction?$transaction->code:null);
    }
	
	 public static function getNextCounterId() {

      $last_count = 1;

      $CODE = 'SPP';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	   //$CODELOCATION = $location_id;
	  
      // get last count
      $spp = Spps::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($spp) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO, $spp->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
}
