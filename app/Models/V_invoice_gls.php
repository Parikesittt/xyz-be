<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class V_invoice_gls extends Model
{
    protected $guarded = [];
    protected $appends = ['general_code', 'general_voucher', 'invoices_datas', 'invoice_number'];

    public function general_code() {
        return $this->belongsTo('General_journals', 'gl_id', 'id');
    }
      
    public function general_voucher() {
        return $this->belongsTo('General_journal_items', 'gl_item_id', 'id');
    }
      
    public function invoices_data() {
        return $this->belongsTo('Invoices', 'invoice_id', 'id');
    }


    public function getGeneralCodeAttribute() {
		$general_journal = $this->general_code()->first();
		return ($general_journal?$general_journal->code:null);
	}

	public function getGeneralVoucherAttribute() {
       $general_journal_item = $this->general_voucher()->first();
       return ($general_journal_item?$general_journal_item->voucher:null);
    }
	
	public function getInvoicesDataAttribute() {
       $invoice = $this->invoices_data()->first();
       return ($invoice?$invoice->code:null);
    }
	
	public function getInvoiceNumberAttribute() {
       $invoice_number = $this->invoices_data()->first();
       return ($invoice_number?$invoice_number->inv_number:null);
    }


    public static function getNextCounterSSId() {
  
        //$last_count = 1;
   
        // get last count
        $set_code = V_invoice_gls::select('kode')
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
