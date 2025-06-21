<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type_files extends Model
{
    protected $guarded = [];

    public function upload_files() {
        return $this->hasMany(Upload_files::class, 'type_file_id', 'id');
    }
}
