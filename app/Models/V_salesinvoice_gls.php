<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class V_salesinvoice_gls extends Model
{
    protected $guarded = [];
    protected $appends = ['general_code', 'general_voucher', 'invoices_datas'];

    public function general_code() {
        return $this->belongsTo(General_journals::class, 'gl_id', 'id');
    }
      
    public function general_voucher() {
        return $this->belongsTo(General_journal_items::class, 'gl_item_id', 'id');
    }
  
    public function invoices_datas() {
        return $this->belongsTo(Salesinvoices::class, 'salesinvoice_id', 'id');
    }


    public function getGeneralCodeAttribute() {
		$general_journal = $this->general_code()->first();
		return ($general_journal?$general_journal->code:null);
	}

	public function getGeneralVoucherAttribute() {
       $general_journal_item = $this->general_voucher()->first();
       return ($general_journal_item?$general_journal_item->voucher:null);
    }
	
	public function getInvoicesDatasAttribute() {
       $salesinvoice = $this->invoices_datas()->first();
       return ($salesinvoice?$salesinvoice->code:null);
    }

    public static function getNextCounterSSId() {
  
        //$last_count = 1;
   
        // get last count
        $set_code = V_salesinvoice_gls::select('kode')
          ->orderBy('kode', 'desc')
          ->first();
  
        if($set_code) {
          $last_count = $set_code->kode + 1;
        }else{
          $last_count = 1;  
        }
  
       // $curr_count = '';
       // $curr_count = sprintf($curr_count + intval($last_count));
        $COUNTER = $last_count;
  
        return $COUNTER;
    }
}
