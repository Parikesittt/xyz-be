<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'customer';
    protected $guarded = [];
    protected $appends = ['attachment_type_name', 'channel_name', 'dbranch_name', 'dgroup_name', 'salesman'];

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
	public function channel(){
        return $this->belongsTo(Channels::class);
    }
    public function dbranch(){
        return $this->belongsTo(Branchs::class, 'branch', 'id');
    }
    public function dgroup(){
        return $this->belongsTo(Customer_head_groups::class, 'head_group', 'code');
    }
    public function salesman(){
        return $this->belongsTo(Salesmans::class);
    }


	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getChannelNameAttribute() {
       $channel = $this->channel()->first();
       return ($channel?$channel->name:null);
    }
  public function getDbranchNameAttribute() {
       $dbranch = $this->dbranch()->first();
       return ($dbranch?$dbranch->name:null);
    }
  public function getDgroupNameAttribute() {
       $dgroup = $this->dgroup()->first();
       return ($dgroup?$dgroup->name:null);
    }
  public function getSalesmanAttribute() {
       $salesman = $this->salesman()->first();
       return ($salesman?$salesman->name:null);
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

	public static function getNextCounterCustomerOfficeId() {


      $last_count = 1;

      $CODE = 'OFCS';

	  //$CODELOCATION = $location_id;

      // get last count
      $customer = Customers::select('accountNum')
        ->where('accountNum','like', $CODE.'%')
        ->orderBy('accountNum', 'desc')
        ->first();



        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($customer) {
        $data = explode($CODE, $customer->accountNum);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%05d', $last_count);
      $COUNTER = $curr_count;

      return $CODE.$COUNTER;
    }
}
