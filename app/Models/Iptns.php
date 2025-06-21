<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iptns extends Model
{
    protected $table = 'iptn';
    protected $guarded = [];
    protected $appends = ['location_name', 'user_name','campboss_name','warehouse_name','warehouse_receive_name'];

    public function items() {
      return $this->hasMany(Items::class, 'unit_id', 'id');
    }

	public function iptn_items(){
        return $this->hasMany(Iptn_items::class, 'iptn_id', 'id');
    }

	public function location() {
      return $this->belongsTo(Locations::class);
    }

	public function warehouse() {
      return $this->belongsTo(Warehouses::class);
    }

	public function user() {
      return $this->belongsTo(Users::class);
    }

	public function receive() {
	  return $this->belongsTo(Warehouses::class, 'warehouse_id_receipt', 'id');
	}


	public function campboss() {
	  return $this->belongsTo(Users::class, 'campboss_id', 'id');
	}

  //-----------------------------------------------------------------------------------------

	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }

	public function getCampbossNameAttribute() {
       $campboss = $this->campboss()->first();
       return ($campboss?$campboss->name:null);
    }

	public function getWarehouseReceiveNameAttribute() {
       $warehouse = $this->receive()->first();
       return ($warehouse?$warehouse->name:null);
    }

	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }

	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }

  //-------------------------------------------------------------------------------------------
	public static function getNextCounterId() {

      /*During the registration, the customer number ID is generated automatically by the system
      (format: C_TAHUN_BULAN_TANGGAL_COUNTER. Example: C201403100001).*/

      $last_count = 1;
      $IPTN= 'IPTN';
	   $IPO = '/';

	   $F = 'F/';
      $TAHUN_BULAN_TANGGAL = date('y');

      // get last count
      $iptn = Iptns::select('no_ipt')
        ->where('no_ipt','like', $IPTN.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.'%')
        ->orderBy('no_ipt', 'desc')
        ->first();

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($iptn) {
        $data = explode($IPTN.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F, $iptn->no_ipt);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $last_count);
      $COUNTER = $curr_count;

      return $IPTN.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$F.$COUNTER;
    }
}
