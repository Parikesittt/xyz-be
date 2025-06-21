<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financial_statement_row_items extends Model
{
    protected $table = 'financial_statement_row_item';
    protected $guarded = [];
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
	public function financial_statement_rows() {
      return $this->belongsTo(Financial_statement_rows::class, 'financial_statement_row_id', 'id');
    }
	public function ledgers(){
        return $this->belongsTo(Ledgers::class, 'ledger_id', 'id');
    }


	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }

	public static function getNextCounterId() {


      $last_count = 1;

      // get last count
      $itemgroup = Itemgroups::select('code')
        ->orderBy('code', 'desc')
        ->first();

	  if($itemgroup) {
        $data = $itemgroup->code;
        $last_count = $data + 1;
      }

      return $last_count;
    }
}
