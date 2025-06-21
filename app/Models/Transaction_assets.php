<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction_assets extends Model
{
    protected $guarded = [];
    protected $appends = ['warehouse_name', 'location_name', 'user_name', 'warehouse_receive_name'];

    public function location() {
        return $this->belongsTo(Locations::class);
      }
      
      public function warehouse() {
        return $this->belongsTo(Warehouses::class);
      }
      
      public function inventory_assets(){
          return $this->hasMany(Inventory_assets::class, 'transaction_id', 'id')
          ->select('inventory_asset.*','unit_convert.factor', 'unit_convert.multiplier')
          ->leftJoin('unit_convert', function ($join) {
              $join->on('inventory_asset.item_code','=','unit_convert.item_code')
                    ->on('inventory_asset.item_unit','=','unit_convert.from_unit' );
          });
      }
      
      public function user(){
          return $this->belongsTo(Users::class);
      }
      
      public function item_code() {
        return $this->belongsTo(Items::class, 'item_code_id', 'id');
      }
      
      public function warehouse_receive() {
        return $this->belongsTo(Warehouses::class, 'warehouse_receive_id', 'id');
      }


      public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	
	public function getWarehouseReceiveNameAttribute() {
       $warehouse_receive = $this->warehouse_receive()->first();
       return ($warehouse_receive?$warehouse_receive->name:null);
    }

	public static function getNextCounterId() {

		

		$last_count = 1;

		$CODE = 'ISA';
		$F ='F/';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transaction_assets::select('code')
					->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($transaction) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterBarcodeId() {

		

		$last_count = 1;

		$CODE = 'ISA';
		$F ='F/';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transaction_assets::select('code')
					->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($transaction) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	//-------------------------------------Transfer Out Asset-----------------------------//
	
	public static function getNextCounterTOAId() {

		

		$last_count = 1;

		$CODE = 'AO';
		$F ='F/';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transaction_assets::select('code')
					->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($transaction) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterBarcodeTOAId() {

		

		$last_count = 1;

		$CODE = 'AO';
		$F ='F/';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transaction_assets::select('code')
					->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($transaction) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	//---------------------------------------Transfer In Asset----------------------------------------//
	
	public static function getNextCounterTIAId() {

		

		$last_count = 1;

		$CODE = 'AI';
		$F ='F/';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transaction_assets::select('code')
					->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($transaction) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterBarcodeTIAId() {

		

		$last_count = 1;

		$CODE = 'AI';
		$F ='F/';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transaction_assets::select('code')
					->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($transaction) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterIOAId() {

		

		$last_count = 1;

		$CODE = 'IOA';
		$F ='F/';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transaction_assets::select('code')
					->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($transaction) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterIIAId() {

		

		$last_count = 1;

		$CODE = 'IIA';
		$F ='F/';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transaction_assets::select('code')
					->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($transaction) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterTHOId() {

		

		$last_count = 1;

		$CODE = 'THO';
		$F ='F/';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transaction_assets::select('code')
					->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($transaction) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterBarcodeTHOId() {

		

		$last_count = 1;

		$CODE = 'THO';
		$F ='F/';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transaction_assets::select('code')
					->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($transaction) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextSAACounterId() {

		

		$last_count = 1;

		$CODE = 'SAA';
		$F ='F/';

		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transaction_assets::select('code')
					->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($transaction) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
}
