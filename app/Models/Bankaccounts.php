<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bankaccounts extends Model
{
    protected $table = 'bankaccounts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'account_id',
        'ledgerAccount',
        'bankGroupId',
        'currencyCode',
        'name',
        'address',
        'accountNum',
        'city',
        'street',
        'dimension_1',
        'dimension_2',
        'dimension_3',
        'company_id',
        'customer_code',
        'user_id',
        'user_update_id',
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
        return $this->hasMany(Sales_orders::class, 'customer_id', 'id');
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
