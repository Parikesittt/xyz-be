<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Closing_datas extends Model
{
    protected $fillable = [
        'id',
        'company_id',
        'fiscal_year_close_id',
        'ledger_account',
        'account_name',
        'debit',
        'credit',
        'amount',
        'user_id',
        'location_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_active',
    ];
}
