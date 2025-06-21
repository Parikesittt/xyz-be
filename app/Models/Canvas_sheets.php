<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canvas_sheets extends Model
{
    protected $guarded = [];
    protected $appends = ['location_name', 'location_code', 'user_name', 'warehouse_name'];

    public function location(){
        return $this->belongsTo(Locations::class);
    }
	public function warehouse(){
        return $this->belongsTo(Warehouses::class);
    }
	public function user(){
        return $this->belongsTo(Users::class);
    }
	
	public function items(){
        return $this->hasMany(Items::class, 'purchase_request_id', 'id');
    }
	public function canvas_sheet_items(){
        return $this->hasMany(Canvas_sheet_items::class, 'canvas_sheet_id', 'id')
					->orderByRaw('canvas_sheet_item.item_code asc');
    }

	
	public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }
	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
    }
	public function getLocationCodeAttribute() {
       $location = $this->location()->first();
       return ($location?$location->code:null);
    }
	public function getWarehouseNameAttribute() {
       $warehouse = $this->warehouse()->first();
       return ($warehouse?$warehouse->name:null);
    }
	
	public static function getNextCounterId() {

		$last_count = 1;

		$CODE = 'CS';
		$IPO = '/';
		$TAHUN_BULAN_TANGGAL = date('y');
	  
		// get last count
		$canvas_sheet = Canvas_sheets::select('code')
					->where('code','like', $CODE.$TAHUN_BULAN_TANGGAL.'%')
					->orderBy('code', 'desc')
					->first();
	  
		if($canvas_sheet) {
			$data = explode($CODE.$TAHUN_BULAN_TANGGAL, $canvas_sheet->code);
			$last_count = intval($data[1]) + 1;
		}

		$curr_count = '';
		$curr_count = sprintf('%08d', $curr_count + intval($last_count));
		$COUNTER = $curr_count;

		return $CODE.$TAHUN_BULAN_TANGGAL.$COUNTER;
    }
}
