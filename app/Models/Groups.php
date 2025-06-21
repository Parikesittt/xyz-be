<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $table = 'group';
    protected $guarded = [];
    protected $appends = ['user_count', 'role_count'];

    public function users() {
      return $this->hasMany(Users::class, 'user_group_id', 'id');
    }

    // Many to Many with group table will need group_role table as pivot table
    public function roles(){
        return $this->belongsToMany(Roles::class, 'group_role', 'group_id', 'role_id');
    }

    public function getUserCountAttribute() {
       $users = $this->users()->count();
       return ($users?intval($users):0);
    }

    public function getRoleCountAttribute() {
       $roles = $this->roles()->count();
       return ($roles?intval($roles):0);
    }
}
