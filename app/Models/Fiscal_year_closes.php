<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fiscal_year_closes extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name', 'ledger_account', 'account_name'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	public function ledgers(){
        return $this->belongsTo(Ledgers::class, 'ledger_id', 'id');
    }
	
	
	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getLedgerAccountAttribute() {
       $ledgers = $this->ledgers()->first();
       return ($ledgers?$ledgers->ledger_account:null);
    }
	public function getAccountNameAttribute() {
       $ledgers = $this->ledgers()->first();
       return ($ledgers?$ledgers->account_name:null);
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
	
	public static function getNextCounterFYCId() {

      $last_count = 1;

      $CODE = 'CLS';
     
	 $IPO = '/';
      $TAHUN_BULAN_TANGGAL = date('y');
	  //$CODELOCATION = $location_id;
	  
      // get last count
      $fiscal_year_close = Fiscal_year_closes::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.'%')
        ->orderBy('code', 'desc')
        ->first();

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($fiscal_year_close) {
        $data = explode($CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO, $fiscal_year_close->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%08d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN_BULAN_TANGGAL.$IPO.$COUNTER;
    }
}
