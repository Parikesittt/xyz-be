<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financial_statement_row_item_totals extends Model
{
    protected $guarded = [];
    
    public function financial_statement_row_items() {
      return $this->belongsTo(Financial_statement_row_items::class, 'financial_statement_row_item_id', 'id');
    }
}
