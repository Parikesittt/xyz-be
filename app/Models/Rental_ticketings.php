<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental_ticketings extends Model
{
    protected $guarded = [];
    protected $appends = ['user_name'];

    public function user() {
      return $this->belongsTo('Users');
    }
	
	public function rental_ticketing_facilitys(){
        return $this->hasMany(Rental_ticketing_facilitys::class, 'rental_ticketing_id', 'id')
					 ->select()
					 ->orderByRaw('id asc');
    }		
	
	public function rental_ticketing_maintenances(){
        return $this->hasMany(Rental_ticketing_maintenances::class, 'rental_ticketing_id', 'id')
					 ->select()
					 ->orderByRaw('id asc');
    }	
	
	public function rental_ticketing_consumables(){
		return $this->hasMany(Rental_ticketing_consumables::class, 'rental_ticketing_id', 'id');
    }

    public function getConsumablesWithItems($limit = null, $offset = null)
    {
        $query = $this->rental_ticketing_consumables()
            ->select('rental_ticketing_consumable.*', 'item.capacity', 'item.std_print')
            ->join('item', function($join) {
                $join->on('rental_ticketing_consumable.item_code', '=', 'item.code')
                     ->on('rental_ticketing_consumable.item_unit', '=', 'item.unit');
            })
            ->orderByRaw('rental_ticketing_consumable.item_code asc');
            
        if ($limit > 0) {
            $query->take($limit)->skip($offset);
        }
        
        return $query->get();
    }
  //-----------------------------------------------------------------------------------------
	
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	
  //-------------------------------------------------------------------------------------------
	public static function getNextCounterId() {

		


		$last_count = 1;

		$CODE = 'TC';
		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
	  
		// get last count
		$rental_ticketing = Rental_ticketings::select('code')
			->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.'%')
			->orderBy('code', 'desc')
			->first();

		if($rental_ticketing) {
			$data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO, $rental_ticketing->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
}
