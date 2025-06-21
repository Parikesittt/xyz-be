<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales_offers extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name', 'user_name', 'location_name', 'warehouse_name', 'customer_name', 'customer_code', 'customer_address', 'customer_terms', 'customer_limit', 'customer_available', 'customer_balance', 'customer_channel', 'cust_type', 'customer_group', 'customer_phone', 'customer_npwp', 'salesman_name'];

    public function atttachment_type() {
		return $this->belongsTo(Attachment_types::class);
    }
	public function user(){
        return $this->belongsTo(Users::class);
    }
	public function location(){
        return $this->belongsTo(Locations::class);
    }
	public function warehouse(){
        return $this->belongsTo(Warehouses::class);
    }
	public function customer(){
        return $this->belongsTo(Customers::class);
    }
	public function salesman(){
        return $this->belongsTo(Salesmans::class,'salesman_id','id');
    }
	public function sales_offer_details(){
        return $this->hasMany(Sales_offer_details::class, 'sales_offer_id', 'id');
    }

    public function getAttachmentTypeNameAttribute() {
		$atttachment_type = $this->atttachment_type()->first();
		return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getUserNameAttribute() {
		$user = $this->user()->first();
		return ($user?$user->name:null);
    }
	public function getLocationNameAttribute() {
		$location = $this->location()->first();
		return ($location?$location->name:null);
    }
	public function getWarehouseNameAttribute() {
		$warehouse = $this->warehouse()->first();
		return ($warehouse?$warehouse->name:null);
    }
	public function getCustomerNameAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->name:null);
    }
	public function getCustomerCodeAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->accountNum:null);
    }
	public function getCustomerAddressAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->address:null);
    }
	public function getCustomerTermsAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->terms:null);
    }
	public function getCustomerLimitAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->limit:null);
    }
	public function getCustomerAvailableAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->available:null);
    }
	public function getCustomerBalanceAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->balance:null);
    }
	public function getCustomerChannelAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->channel_id:null);
    }
	public function getCustTypeAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->type:null);
    }
	public function getCustomerGroupAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->head_group:null);
    }
	public function getLimitSoAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->limit_so:null);
    }
	public function getLimitInvoiceAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->limit_invoice:null);
    }
	public function getCustomerPhoneAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->phone:null);
    }
	public function getCustomerNpwpAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->npwp:null);
    }
	public function getCustomerBranchAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->branch:null);
    }
	public function getLimitCashAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->limit_cash:null);
    }
	public function getCustomerInvoicegroupAttribute() {
		$customer = $this->customer()->first();
		return ($customer?$customer->invoice_group:null);
    }
	public function getSalesmanNameAttribute() {
		$salesman = $this->salesman()->first();
		return ($salesman?$salesman->name:null);
    }
	
	public static function getNextCounterId($location_id) {
		
		
		$last_count = 1;
		
		$CODE = 'QT';
		$F ='F/';
		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
		$CODELOCATION = $location_id;
		
		// get last count
		$sales_offer = Sales_offers::select('code')
			->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
			->orderBy('code', 'desc')
			->first();
		
		if($sales_offer) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $sales_offer->code);
			$last_count = intval($data[1]) + 1;
		}
		
		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;
		
		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
}
