<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multiple_prints extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	
	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
}
