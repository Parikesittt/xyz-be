<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $guarded = [];
    protected $appends = ['user_name', 'sender_name'];
    protected $softDelete = true;
    

    public function user(){
        return $this->belongsTo(Users::class);
    }

    public function sender(){
        return $this->belongsTo(Users::class);
    }


    public function getUserNameAttribute() {
       $user = $this->user()->first();
       return ($user?$user->name:null);
    }

    public function getSenderNameAttribute() {
       $sender = $this->sender()->first();
       return ($sender?$sender->name:null);
    }
}
