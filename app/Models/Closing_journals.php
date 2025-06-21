<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Closing_journals extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'company_id',
        'date',
        'ledger_id',
        'ledger_account',
        'account_name',
        'debit',
        'credit',
        'net_dif',
        'debit_all',
        'credit_all',
        'location_id',
        'warehouse_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_active',
    ];
    protected $appends = ['company_code'];

    public function company() {
      return $this->belongsTo(Companys::class);
    }

	
	public function getCompanyCodeAttribute() {
       $company = $this->company()->first();
       return ($company?$company->code:null);
    }
}
