<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory_postings extends Model
{
    protected $guarded = [];
    protected $appends = ['user_name', 'transaction_name'];

    public function user() {
      return $this->belongsTo(Users::class);
    }
	
	public function transaction_type() {
	  return $this->belongsTo(Transaction_types::class, 'TransactionType', 'id');
	}
	
	
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	
	public function getTransactionNameAttribute() {
       $transaction_type = $this->transaction_type()->first();
       return ($transaction_type?$transaction_type->name:null);
    }
}
