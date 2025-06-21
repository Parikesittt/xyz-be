<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    protected $table = 'brand';
    protected $fillable = [
        'id',
        'code',
        'name',
        'phone',
        'company',
        'address',
        'product',
        'situs',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_active',
    ];

    public static function getNextBrandCode() {
        $last_count = 1;

        $brand = self::select('code')
            ->orderBy('id', 'desc')
            ->first();

        if ($brand) {
            $data = $brand->code;
            $last_count = intval($data) + 1;
        }

        $curr_count = '';
        $curr_count = sprintf("%03d", $curr_count + intval($last_count));
        $COUNTER = $curr_count;

        return $COUNTER;
    }


}
