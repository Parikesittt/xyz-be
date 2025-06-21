<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload_file_uploads extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name'];

    public function upload_file() {
        return $this->belongsTo(Upload_files::class);
    }
  
    public function attachment_type() {
        return $this->belongsTo(Attachment_types::class);
    }

    
    public function getAttachmentTypeNameAttribute() {
        $attachment_type = $this->attachment_type()->first();
        return ($attachment_type?$attachment_type->name:null);
    }
}
