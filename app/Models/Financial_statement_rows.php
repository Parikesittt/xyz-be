<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financial_statement_rows extends Model
{
    protected $table = 'financial_statement_row';
    protected $guarded = [];
    protected $appends = ['attachment_type_name', 'name', 'code'];

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
	public function financial_statement_row_items(){
        return $this->hasMany(Financial_statement_row_items::class, 'financial_statement_row_id', 'id')
					->orderByRaw('financial_statement_row_item.position asc');
    }

	 public function financial_statements(){
        return $this->belongsTo(Financial_statements::class, 'financial_statement_id', 'id');
    }


	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getCodeAttribute() {
       $financial_statements = $this->financial_statements()->first();
       return ($financial_statements?$financial_statements->code:null);
    }
	public function getNameAttribute() {
       $financial_statements = $this->financial_statements()->first();
       return ($financial_statements?$financial_statements->name:null);
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
