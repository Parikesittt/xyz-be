<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction_temps extends Model
{
    protected $guarded = [];
    protected $appends = ['location_from','pmcs_out_code','warehouse_name', 'location_name', 'user_name','supplier_name', 'warehouse_receive_name','to_warehouse','journal_name','journal_description','ppn_item','so_code','customer_id','customer_name','transaction_code'];

    public function location() {
        return $this->belongsTo(Locations::class);
      }
      
      public function journal() {
       return $this->belongsTo(Journals::class, 'department_id', 'id');
      }
      public function pmcs_out() {
       return $this->belongsTo(Transactions::class, 'pmcs_out_id', 'id');
      }
      
      public function warehouse() {
        return $this->belongsTo(Warehouses::class);
      }
      
      public function sales_order() {
        return $this->belongsTo(Sales_orders::class,'so_id','id');
      }
      
      public function supplier() {
        return $this->belongsTo(Suppliers::class);
      }
      public function inventory_temps(){
          return $this->hasMany(Inventory_temps::class, 'transaction_id', 'id')
                      ->select('inventory_temp.*','stock.qty as stock','stock.avg_price as hpp','item.product_code')
                      ->join('item','item.code','=','inventory_temp.item_code')
                      ->leftJoin('stock', function ($join){
                          $join->on('inventory_temp.item_code','=','stock.item_code')
                               ->on( 'inventory_temp.warehouse_id','=','stock.warehouse_id');
                          });
      }
      
      public function overheads(){
          return $this->hasMany(Overheads::class, 'pmcs_in_id', 'id');
      }
  
    public function parameters(){
          return $this->hasMany(Parameters::class, 'pmcs_in_id', 'id');
      }
      
      public function transaction_rr() {
        return $this->belongsTo(Transactions::class, 'rr_id', 'id');
      }
      
      public function user(){
          return $this->belongsTo(Users::class);
      }
      
      public function receive() {
        return $this->belongsTo(Warehouses::class, 'warehouse_receive_id', 'id');
      }
      
      public function warehouse_to() {
        return $this->belongsTo(Warehouses::class, 'to_warehouse_id', 'id');
      }
      
      public function transaction_type() {
        return $this->belongsTo(Transaction_types::class, 'transaction_type', 'id');
      }
      
      public function item_code() {
        return $this->belongsTo(Items::class, 'item_code_id', 'id');
      }
      
      public function customer() {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
      }


      public function getTransactionCodeAttribute() {
       $transaction_rr = $this->transaction_rr()->first();
       return ($transaction_rr?$transaction_rr->code:null);
    }
	
  
	public function getSupplierNameAttribute() {
       $supplier = $this->supplier()->first();
       return ($supplier?$supplier->name:null);
    }
	
	public function getSoCodeAttribute() {
       $sales_order = $this->sales_order()->first();
       return ($sales_order?$sales_order->code:null);
    }
	public function getPmcsOutCodeAttribute() {
       $pmcs_out = $this->pmcs_out()->first();
       return ($pmcs_out?$pmcs_out->code:null);
    }
	
	public function getCustomerNameAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->name:null);
    }
	
	public function getCustomerIdAttribute() {
       $sales_order = $this->sales_order()->first();
       return ($sales_order?$sales_order->customer_id:null);
    }
	
	public function getPpnItemAttribute() {
       $supplier = $this->item_code()->first();
	   //var_dump($supplier);
       return ($supplier?$supplier->item_sales_tax:null);
    }
	
	public function getLocationFromAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	
	public function getTransactionTypeNameFromAttribute() {
       $transaction_type = $this->transaction_type()->first();
       return ($transaction_type?$transaction_type->name:null);
    }
	
	public function getJournalNameAttribute() {
       $journal = $this->journal()->first();
       return ($journal?$journal->name:null);
    }
	
	public function getJournalDescriptionAttribute() {
       $journal = $this->journal()->first();
       return ($journal?$journal->description:null);
    }
	
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	

	
	public function getToWarehouseAttribute() {
       $warehouse = $this->warehouse_to()->first();
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
       $warehouse = $this->receive()->first();
       return ($warehouse?$warehouse->name:null);
    }
  
  public static function getNextCounterId() {

      


      $last_count = 1;

      $CODE = 'IJ';
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterRRId() {

      


      $last_count = 1;

      $CODE = 'RR';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
  
  public static function getNextCounterPLId() {

      


      $last_count = 1;

      $CODE = 'PB';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	
	public static function getNextCounterTIId() {

		


		$last_count = 1;

		$CODE = 'TI';
		
		$F ='F/';

     
		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('ym');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transactions::select('code')
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
	
	//------------------------------------------------------------------------------
	public static function getNextCodeId($location_id) {

      


      $last_count = 1;

      $CODE = 'RR';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  $CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$CODELOCATION.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$CODELOCATION.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$CODELOCATION.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	//----------------------------------------------------------------------------------------------------
	
	public static function getNextCodeAdjustment() {

      


      $last_count = 1;

      $CODE = 'AJ';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transaction_temps::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('code', 'desc')
        ->first();

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterPMCSOutId() {

      


      $last_count = 1;

      $CODE = 'AJ';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transaction_temps::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('code', 'desc')
        ->first();

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCodeSpoiler() {

      


      $last_count = 1;

      $CODE = 'SL';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	 // $CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
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
	
	public static function getNextCodeIPTO() {

      


      $last_count = 1;

      $CODE = 'IPTO';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('code', 'desc')
        ->first();

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCodeIPTIN() {

      


      $last_count = 1;

      $CODE = 'IPTIN';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('code', 'desc')
        ->first();

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCodeSpoilerBarcode() {

      


      $last_count = 1;

      $CODE = 'SL';
	  
	  $F ='F/';

	  //$IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->orderBy('code', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
	
	public static function getNextCounterRRBarcodeId() {

      


      $last_count = 1;

      $CODE = 'RR';
	  
	  $F ='F/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->orderBy('code', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
	
	public static function getNextBarcodeTOId() {

      


      $last_count = 1;

      $CODE = 'IJ';
	  
	  $F ='F/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->orderBy('code', 'desc')
        ->first();

      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
	
	public static function getNextBarcodeTIId() {

		


		$last_count = 1;

		$CODE = 'TI';
		
		$F ='F/';

     
		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('ym');
		//$CODELOCATION = $location_id;
	  
		// get last count
		$transaction = Transactions::select('code')
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
	
	public static function getNextCounterIssuedId() {

      


      $last_count = 1;

      $CODE = 'IS';
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	 // $CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
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
	
	public static function getNextCounterIssuedBarcodeId() {

      


      $last_count = 1;

      $CODE = 'IS';
	  $F ='F/';

	  //$IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->orderBy('code', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
	
	public static function getNextCounterIBackchargeId() {

      


      $last_count = 1;

      $CODE = 'BC';
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	 // $CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
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
	
	/* public static function getNextCounterBackchargeBarcodeId() {

      


      $last_count = 1;

      $CODE = 'BC';

	  //$IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.'%')
        ->orderBy('code', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$COUNTER;
    } */
	
	public static function getNextCounterAdjustmentBarcodeId() {

      


      $last_count = 1;

      $CODE = 'AJ';
	  
	  $F ='F/';

	  //$IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transaction_temps::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->orderBy('code', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
	
	public static function getNextCodeIPTNOutBarcode() {

      


      $last_count = 1;

      $CODE = 'IPTO';
	  
	  $F ='F/';

	  //$IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('barcode')
        ->where('barcode','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->orderBy('barcode', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->barcode);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
	
	public static function getNextCodeIPTNInBarcode() {

      


      $last_count = 1;

      $CODE = 'IPTIN';
	  
	  $F ='F/';

	  //$IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('barcode')
        ->where('barcode','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->orderBy('barcode', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->barcode);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
	
	public static function getNextCounterRTId() {

      


      $last_count = 1;

      $CODE = 'RT';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterRTBarcodeId() {

      


      $last_count = 1;

      $CODE = 'RT';
	  
	  $F ='F/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->orderBy('code', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
	
	public static function getNextCounterBackchargeId() {

      


      $last_count = 1;

      $CODE = 'BC';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterBackchargeBarcodeId() {

      


      $last_count = 1;

      $CODE = 'BC';
	  $F ='F/';
	  
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transactions::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->orderBy('code', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
	
	public static function getNextCounterReturPOId() {

      


      $last_count = 1;

      $CODE = 'RTP';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transaction_temps::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->where('transaction_type','=', 21)
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterReturPOBarcodeId() {

      


      $last_count = 1;

      $CODE = 'RT';
	  
	  $F ='F';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transaction_temps::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->where('transaction_type','=', 21)
        ->orderBy('code', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
	
	public static function getNextCounterIssuedAssetId() {

      


      $last_count = 1;

      $CODE = 'IS';
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	 // $CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transaction_temps::select('code')
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
	
	public static function getNextCounterIssuedAssetBarcodeId() {

      


      $last_count = 1;

      $CODE = 'IS';
	  $F ='F/';

	  //$IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transaction_temps::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->orderBy('code', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
	
	public static function getNextCounterReturDOId() {

      


      $last_count = 1;

      $CODE = 'RT';
	  
	  $F ='F/';

     
	  $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transaction_temps::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->where('transaction_type','=', 22)
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($transaction) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
	
	public static function getNextCounterReturDOBarcodeId() {

      


      $last_count = 1;

      $CODE = 'RT';
	  
	  $F ='F/';
      $TAHUN_BULAN_TANGGAL = date('ym');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $transaction = Transaction_temps::select('code')
        ->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.$F.'%')
        ->where('transaction_type','=', 22)
        ->orderBy('code', 'desc')
        ->first();


      if($transaction) {
        $data = explode($CODE.$TAHUN_BULAN_TANGGAL.$F, $transaction->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$TAHUN_BULAN_TANGGAL.$F.$COUNTER;
    }
}
