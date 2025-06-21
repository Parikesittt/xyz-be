<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouses extends Model
{
    protected $table = 'warehouse';
    protected $guarded = [];

    // protected $appends = ['location_name, location_code, customer_name'];

    public function price_lists() {
        return $this->hasMany(Price_lists::class, 'warehouse_id', 'id');
    }

    public function users() {
        return $this->hasMany(Users::class, 'warehouse_id', 'id');
    }

    public function purchase_requests() {
        return $this->hasMany(Purchase_requests::class, 'warehouse_id', 'id');
    }

    public function transactions() {
        return $this->hasMany(Transactions::class, 'warehouse_id', 'id');
    }

    public function sales_orders() {
        return $this->hasMany(Sales_orders::class, 'warehouse_id', 'id');
    }

    public function purchase_request_months() {
        return $this->hasMany(Purchase_request_months::class, 'warehouse_id', 'id');
    }

    public function invetorys() {
        return $this->hasMany(Inventorys::class, 'warehouse_id', 'id');
    }

    public function location() {
        return $this->belongsTo(Locations::class);
    }

    public function customer() {
        return $this->belongsTo(Customers::class);
    }

    public function warehouse_project_groups() {
        return $this->hasMany(Warehouse_project_groups::class, 'warehouse_id', 'id');
    }


    // public function getLocationNameAttribute() {
    //     $location = $this->location()->first();
    //     return $location ? $location->name : null;
    // }

    // public function getLocationCodeAttribute() {
    //     $location = $this->location()->first();
    //     return $location ? $location->code : null;
    // }

    // public function getCustomerNameAttribute() {
    //     $customer = $this->customer()->first();
    //     return $customer ? $customer->name : null;
    // }

    public static function getNextCodeId($location_id, $warehouse_type) {
        $last_count = 1;

        $code = Locations::select('code')
            ->where('id', '=', $location_id)
            ->first();

        if ($warehouse_type == 1) {
            // For warehouse_type 1
            $warehouses = Warehouses::select('code')
                ->where('code', 'like', $code->code . 'W%')
                ->orderBy('code', 'desc')
                ->first();

            if ($warehouses) {
                $data = explode($code->code . 'W', $warehouses->code);
                $last_count = intval($data[1]) + 1;
            }

            $curr_count = 0; // Initialize as an integer
            $curr_count = sprintf('%02d', $curr_count + intval($last_count));
            $COUNTER = $curr_count;

            return $code->code . 'W' . $COUNTER;

        } else {
            // For other warehouse types
            $warehouses = Warehouses::select('code')
                ->where('code', 'like', $code->code . 'S%')
                ->orderBy('code', 'desc')
                ->first();

            if ($warehouses) {
                $data = explode($code->code . 'S', $warehouses->code);
                $last_count = intval($data[1]) + 1;
            }

            $curr_count = 0; // Initialize as an integer
            $curr_count = sprintf('%02d', $curr_count + intval($last_count));
            $COUNTER = $curr_count;

            return $code->code . 'S' . $COUNTER;
        }
    }
}
