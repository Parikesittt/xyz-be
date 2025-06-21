<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Home_admins extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name'];

    public function attachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	
	
	public function getAttachmentTypeNameAttribute() {
       $attachment_type = $this->attachment_type()->first();
       return ($attachment_type?$attachment_type->name:null);
    }
}
