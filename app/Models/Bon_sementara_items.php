<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bon_sementara_items extends Model
{
    protected $fillable = [
        'id',
        'bon_sementara_id',
        'tgl',
        'bon_sementara_code',
        'tax_transaksi',
        'kode_perkiraan',
        'mata_uang',
        'jml',
        'no_pp',
        'kegiatan_anggaran',
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
        return $this->hasMany(Sales_orders::class, 'sales_id', 'id');
    }
	
	public function bon_sementaras() {
      return $this->belongsTo(Bon_sementaras::class, 'bon_sementara_id', 'id');
    }


	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
}
