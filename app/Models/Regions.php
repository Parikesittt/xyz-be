<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    protected $guarded = [];
    protected $appends = ['company_code'];

    public function company() {
        return $this->belongsTo(Companys::class);
    }

    public function getCompanyCodeAttribute() {
        $company = $this->company()->first();
        return $company ? $company->code : null;
    }
}
