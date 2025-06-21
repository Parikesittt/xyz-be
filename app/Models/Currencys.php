<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currencys extends Model
{
    protected $table = 'currency';
    protected $fillable = [
        'id',
        'code',
        'description',
        'gender',
        'iso_currency_code',
        'symbol',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_active',
    ];

    public function currency_items(){
        return $this->hasMany(Currency_items::class, 'currency_id', 'id')
					->orderByRaw('currency_item.start_date desc');
    }
}
