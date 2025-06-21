<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $guarded = [];
    protected $appends = ['route_name', 'route_label'];

    public function groups(){
        return $this->belongsToMany(Groups::class, 'group_role', 'role_id', 'group_id');
    }

    public function route() {
      return $this->belongsTo(Routes::class);
    }

    public function getRouteNameAttribute() {
         $route = $this->route()->first();
      return ($route?$route->name:null);
    }

    public function getRouteLabelAttribute() {
         $route = $this->route()->first();
      return ($route?$route->label:null);
    }
}
