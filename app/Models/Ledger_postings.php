<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger_postings extends Model
{
    protected $guarded = [];
    protected $appends = ['attachment_type_name', 'line_property', 'project_group'];

    public function atttachment_type() {
      return $this->belongsTo(Attachment_types::class);
    }
	
	
	public function getAttachmentTypeNameAttribute() {
       $atttachment_type = $this->atttachment_type()->first();
       return ($atttachment_type?$atttachment_type->name:null);
    }
	public function getLinePropertyAttribute() {
       $line_property = $this->line_property()->first();
       return ($line_property?$line_property->line_property:null);
    }
	public function getProjectGroupAttribute() {
       $project_group = $this->project_group()->first();
       return ($project_group?$project_group->project_group:null);
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
}
