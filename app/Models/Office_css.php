<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office_css extends Model
{
    protected $table = 'office_cs';
    protected $guarded = [];
    protected $appends = ['customer_name', 'customer_email', 'customer_phone'];

    public function office_parts() {
      return $this->hasMany(Office_parts::class, 'cs_id', 'id');
    }

	public function customer() {
	  return $this->belongsTo(Customers::class, 'customer_id', 'id');
	}


	public function getCustomerNameAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->name:null);
    }

    public function getCustomerEmailAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->email:null);
    }

    public function getCustomerPhoneAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->phone:null);
    }

	public static function getNextCode($location_id) {

		$last_count = 1;

		$location = Locations::select()->where('id',$location_id)->first();
		/* $LOC = substr($location->name,0,1); */
		$LOC = $location->code_service;

		$CODE = 'SX';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('ym');

		$office_cs = Office_css::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$LOC.$IPO.'%')
        ->where('location_id', $location_id)
		->orderBy('code', 'desc')
		->first();

		if($office_cs) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$LOC.$IPO, $office_cs->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%06d', $last_count);
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$LOC.$IPO.$COUNTER;
	}
}
