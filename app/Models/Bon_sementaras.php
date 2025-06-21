<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bon_sementaras extends Model
{
    protected $fillable = [
        'id',
        'code',
        'bs_journal_name_id',
        'mata_uang',
        'periode',
        'nip',
        'no_sop',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $appends = ['attachment_type_name', 'approval_type', 'description', 'transaction_type'];

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
	public function bs_journal_names(){
        return $this->belongsTo(Bs_journal_names::class, 'bs_journal_name_id', 'id');
    } 
	public function bon_sementara_items(){
        return $this->hasMany(Bon_sementara_items::class, 'bon_sementara_id', 'id');
    }
	
	
	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getApprovalTypeAttribute() {
       $bs_journal_names = $this->bs_journal_names()->first();
       return ($bs_journal_names?$bs_journal_names->approval_type:null);
    }
	public function getDescriptionAttribute() {
       $bs_journal_names = $this->bs_journal_names()->first();
       return ($bs_journal_names?$bs_journal_names->description:null);
    }
	public function getTransactionTypeAttribute() {
       $bs_journal_names = $this->bs_journal_names()->first();
       return ($bs_journal_names?$bs_journal_names->transaction_type:null);
    }
	public static function getNextCounterId() {

      /* $app = \Slim\Slim::getInstance();


      $last_count = 1;
	  
      // get last count
      $itemgroup = Itemgroups::select('code')
        ->orderBy('code', 'desc')
        ->first();
	  
	  if($itemgroup) {
        $data = $itemgroup->code;
        $last_count = $data + 1;
      }

      return $last_count; */

      $last_count = 1;

      $CODE = 'BS';

     
	  $IPO = '/';
	  $TAHUN = date('y');
	  $BULAN = date('m');
	  /* $CODELOCATION = $location_id; */
	  
      // get last count
      $bon_sementara = Bon_sementaras::select('code')
        ->where('code','like', $CODE.$IPO.$TAHUN.$IPO.$BULAN.$IPO.'%')
        ->orderBy('code', 'desc')
        ->first();
	  
	  

        // debug sql
        //$queries = $app->db->getQueryLog();
        //var_dump( $queries);die();
        //$last_query = end($queries);
        //var_dump( $last_query);die();

      if($bon_sementara) {
        $data = explode($CODE.$IPO.$TAHUN.$IPO.$BULAN.$IPO, $bon_sementara->code);
        $last_count = intval($data[1]) + 1;
      }

      $curr_count = '';
      $curr_count = sprintf('%04d', $curr_count + intval($last_count));
      $COUNTER = $curr_count;

      return $CODE.$IPO.$TAHUN.$IPO.$BULAN.$IPO.$COUNTER;
	  
    }
}
