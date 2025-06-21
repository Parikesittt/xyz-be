<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class General_journal_items extends Model
{
    protected $guarded = [];
    protected $appends = ['location_name', 'user_name', 'warehouse_name'];

    public function location() {
      return $this->belongsTo(Locations::class);
    }
	
	public function warehouse() {
      return $this->belongsTo(Warehouses::class);
    }
	
	public function user() {
      return $this->belongsTo(Users::class);
    }
		
	public function general_journals() {
      return $this->belongsTo(General_journals::class, 'general_journal_id', 'id');
    }
	
  //-----------------------------------------------------------------------------------------

	public function getLocationNameAttribute() {
       $location = $this->location()->first();
       return ($location?$location->name:null);
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
  public static function getNextCounterVoucherId($code, $userCompanyId)
  {
      $lastCount = 1;
      $separator = '/';
      $yearMonth = date('ym');
      
      // Get last count using Laravel's query builder
      $journalInvoice = Journal_invoice_items::select('voucher')
          ->where('company_id', '=', $userCompanyId);
          
      $cdv = Cdvs::select('voucher')
          ->where('company_id', '=', $userCompanyId);
          
      $rv = Received_vouchers::select('voucher')
          ->where('company_id', '=', $userCompanyId);
          
      $query = self::select('voucher')
          ->where('company_id', '=', $userCompanyId)
          ->union($journalInvoice)
          ->union($cdv)
          ->union($rv);
          
      $voucher = DB::table(DB::raw("({$query->toSql()}) as general_journal_item"))
          ->mergeBindings($query->getQuery())
          ->where('voucher', 'like', '%' . $yearMonth . $separator . $code)
          ->orderBy('voucher', 'desc')
          ->first();
          
      if ($voucher) {
          $data = explode($separator . $yearMonth . $separator . $code, $voucher->voucher);
          $lastCount = intval($data[0]) + 1;
      }

      $counter = sprintf('%04d', $lastCount);
      
      return $counter . $separator . $yearMonth . $separator . $code;
  }
}
