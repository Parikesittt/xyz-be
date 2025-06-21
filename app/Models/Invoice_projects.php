<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice_projects extends Model
{
    protected $guarded = [];
    protected $appends = ['customer_name', 'customer_accountNum', 'bank_name'];

    public function customer(){
        return $this->belongsTo(Customers::class);
    }
	
	public function bankaccount(){
        return $this->belongsTo(Bankaccounts::class);
    }
	
	public function invoice_project_items(){
		 return $this->hasMany(Invoice_project_items::class, 'invoice_project_id', 'id')
					->select('invoice_project_item.*','invoice_project_item.sales_prices as shadow_sales_prices');
    }

	public function getCustomerNameAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->name:null);
    }
	
	public function getBankNameAttribute() {
       $bankaccount = $this->bankaccount()->first();
       return ($bankaccount?$bankaccount->name:null);
    }
	
	public function getCustomerAccountNumAttribute() {
       $customer = $this->customer()->first();
       return ($customer?$customer->accountNum:null);
    }

    public static function getNextCounterId($user_company_id,$z) {
	
      /*During the registration, the customer number ID is generated automatically by the system 
      (format: C_TAHUN_BULAN_TANGGAL_COUNTER. Example: C201403100001).*/

      $last_count = 1;
      $code = 'INVP';
      $IPO = '/';
      //$TAHUN_BULAN_TANGGAL = $z;

      $invoice_project = Invoice_projects::select('code')
        ->where('code','like', $code.$IPO.$z.$IPO.'%')
        ->orderBy('code', 'desc')
        ->first();

      if($invoice_project) {
        $data = explode($code.$IPO.$z.$IPO, $invoice_project->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $code.$IPO.$z.$IPO.$COUNTER;
    }
}
