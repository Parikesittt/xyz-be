<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Companys extends Model
{
    protected $table = 'Company';
    protected $fillable = [
        'id',
        'code',
        'name',
        'address',
        'phone',
        'email',
        'file_path',
        'file_type',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_active',
        'bank',
    ];

    public function upload_files() {
      return $this->hasMany(Upload_files::class, 'upload_file_id', 'id');
    }
}
