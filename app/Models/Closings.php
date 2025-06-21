<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Closings extends Model
{
    protected $fillable = [
        'id',
        'code',
        'month',
        'user_id',
        'is_closing',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_active',
        'company_id',
    ];
    protected $appends = ['location_name', 'user_name'];

    public function location() {
      return $this->belongsTo(Locations::class);
    }
	
	public function user() {
      return $this->belongsTo(Users::class);
    }
	

	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	

	public static function getNextCodeId() {

		  $last_count = 1;

		  $CODE = 'CL';
		 
		  $IPO = '/';
		  $TAHUN_BULAN_TANGGAL = date('y');
		  
		  // get last count
		  $closing = Closings::select('code')
			->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.'%')
			->orderBy('code', 'desc')
			->first();
		 
			// debug sql
			//$queries = $app->db->getQueryLog();
			//var_dump( $queries);die();
			//$last_query = end($queries);
			//var_dump( $last_query);die();

		  if($closing) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO, $closing->code);
			$last_count = intval($data[1]) + 1;
		  }

		  $curr_count = '';
		  $curr_count = sprintf('%08d', $curr_count + intval($last_count));
		  $COUNTER = $curr_count;

		  return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
	}
}
