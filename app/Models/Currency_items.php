<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency_items extends Model
{
    protected $fillable = [
        'id',
        'currency_id',
        'start_date',
        'end_date',
        'exchange_rate',
        'reciprocal_value',
        't',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $appends = ['attachment_type_name'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	public function itemcategorys(){
        return $this->hasMany(Itemcategorys::class, 'itemgroup_id', 'id');
    }
	public function subcategorys(){
        return $this->hasMany(Subcategorys::class, 'itemgroup_id', 'id');
    }
	public function items(){
        return $this->hasMany(Items::class, 'itemgroup_id', 'id');
    }
	public function sales_orders(){
        return $this->hasMany(Sales_orders::class, 'sales_id', 'id');
    }
	
	public function currencys() {
      return $this->belongsTo(Currencys::class, 'currency_id', 'id');
    }

	
	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
}
