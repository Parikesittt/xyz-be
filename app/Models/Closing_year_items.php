<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Closing_year_items extends Model
{
    protected $fillable = [
        'id',
        'date',
        'company_id',
        'closing_year_id',
        'accountNum',
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
