<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spp_ars extends Model
{
    protected $guarded = [];
    protected $appends = ['customer_name'];

    public function upload_files() {
      return $this->hasMany(Upload_files::class, 'upload_file_id', 'id');
    }
	
    public function customer() {
      return $this->belongsTo(Customers::class);
    }
	
	public function salesinvoice_items(){
        return $this->hasMany(Salesinvoices::class, 'spp_id', 'id');
    }

    public function getCustomerNameAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->name:null);
    }
	
	 public static function getNextCounterId() {

      $last_count = 1;

      $CODE = 'KWT';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	   //$CODELOCATION = $location_id;
	  
      // get last count
      $spp = Spp_ars::select('code')
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
