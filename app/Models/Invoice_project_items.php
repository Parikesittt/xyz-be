<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice_project_items extends Model
{
    protected $guarded = [];
    protected $appends = ['invoice_code'];

    public function invoice_code() {
      return $this->belongsTo(Invoice_projects::class, 'invoice_project_id', 'id');
    }

	public function getInvoiceCodeAttribute() {
		$invoice_code = $this->invoice_code()->first();
		return ($invoice_code?$invoice_code->code:null);
	}
}
