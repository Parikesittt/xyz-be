<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class cdv_items extends Model
{
    protected $fillable = [
        'id',
        'cdv_id',
        'code',
        'spp_id',
        'invoice_id',
        'no_faktur',
        'description',
        'amount',
        'ppn_price',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_active',
    ];
    protected $appends = ['supplier_name'];

    public function upload_files() {
      return $this->hasMany(Upload_files::class, 'upload_file_id', 'id');
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
