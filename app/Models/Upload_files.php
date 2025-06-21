<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload_files extends Model
{
    protected $guarded = [];
    protected $appends = ['company_name', 'type_file_name'];

    public function upload_file_uploads(){
        return $this->hasMany(Upload_file_uploads::class, 'document_id', 'id');
    }
	
    public function company(){
        return $this->belongsTo(Companys::class);
    }
	public function type_file(){
        return $this->belongsTo(Type_files::class);
    }


    public function getCompanyNameAttribute() {
        $company = $this->company()->first();
        return ($company?$company->name:null);
    }
    public function getTypeFileNameAttribute() {
        $type_file = $this->type_file()->first();
        return ($type_file?$type_file->name:null);
        return ($type_file?$type_file->name:null);
    }
}
