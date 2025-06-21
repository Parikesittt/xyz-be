<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Users extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'api';

    protected $guarded = [];
    protected $hidden = ['password'];
    protected $appends = ['group', 'admin', 'can_read', 'can_create', 'can_update', 'can_delete', 'has_roles','routers','location_name','warehouse_name','warehouse_code','customer_name'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getPermissionArray()
    {
        return $this->getAllPermissions()->mapWithKeys(function($pr){
            return [$pr['name'] => true];
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
        
    /**
     * getJWTCustomClaims
     *
     * @return void
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getPassword() {
        return $this->password;
    }


    public function user_group(){
        return $this->belongsTo(Groups::class);
    }
	
	public function location(){
        return $this->belongsTo(Locations::class);
    }
	
	public function warehouse(){
        return $this->belongsTo(Warehouses::class);
    }
	
	public function user_apps() {
       return $this->hasMany(User_apps::class, 'user_id', 'id');
    }

    public function notificationsAsUser() {
       return $this->hasMany(Notifications::class, 'user_id', 'id');
    }
	public function purchase_requests() {
       return $this->hasMany(Purchase_requests::class, 'user_id', 'id');
    }

    public function notificationsAsSender() {
       return $this->hasMany(Notifications::class, 'sender_user_id', 'id');
    }
	
	public function user_projects(){
        return $this->hasMany(User_projects::class, 'user_id', 'id');
    }
	
	public function customer() {
	  return $this->belongsTo(Customers::class, 'customer_id', 'id');
	}


    public function getUserGroupAttribute() {
        $user_group = $this->user_group()->first();
        return ($user_group?$user_group->toArray():null);
     }
 
     public function getUserAppsAttribute() {
        $user_apps = $this->user_apps()->get();
        return ($user_apps?$user_apps->toArray():array());
     }
 
     public function getGroupAttribute() {
          $user_group = $this->user_group()->first();
            return $user_group->name;
        }
     
     public function getLocationNameAttribute() {
        $location = $this->location()->first();
        return ($location?$location->name:null);
     }
     
     public function getWarehouseNameAttribute() {
        $warehouse = $this->warehouse()->first();
        return ($warehouse?$warehouse->name:null);
     }
     public function getWarehouseCodeAttribute() {
        $warehouse = $this->warehouse()->first();
        return ($warehouse?$warehouse->code:null);
     }

     public function getAdminAttribute() {
        $user_group = $this->user_group()->first();
        return 1; //(($user_group->can_read==1 && $user_group->can_create==1 && $user_group->can_update==1 && $user_group->can_delete==1)?true:false);
     }
 
       public function getCanReadAttribute() {
        $user_group = $this->user_group()->first();
        return 1; //$user_group->can_read;
     }
 
     public function getCanCreateAttribute() {
        $user_group = $this->user_group()->first();
        return 1; //$user_group->can_create;
     }
 
     public function getCanUpdateAttribute() {
        $user_group = $this->user_group()->first();
        return 1; //$user_group->can_update;
     }
 
     public function getCanDeleteAttribute() {
        $user_group = $this->user_group()->first();
        return 1; //$user_group->can_delete;
     }
     
     public function getCustomerNameAttribute() {
        $customer = $this->customer()->first();
        return ($customer?$customer->name:null);
     }
     
     public function getHasRolesAttribute() {
        $user_group = $this->user_group()->with('roles')->first();
        $roles = array();
 
         // get all available roles
         foreach($user_group->roles as $role) {
             $roles[] = $role->name;
         }
 
        return $roles;
     }

     public function getRoutersAttribute() {
        $user_group = $this->user_group()->with('roles')->first();
        $roles = array();
 
         // get all available roles
         foreach($user_group->roles as $role) {
           if($role->route_name) {
             $roles[] = array(
               'route' => $role->route_name,
               'can_create' => !(!$role->can_create),
               'can_read' => !(!$role->can_read),
               'can_update' => !(!$role->can_edit),
               'can_delete' => !(!$role->can_delete),
               'can_approved' => !(!$role->can_approved),
             );            
           }
         }
        return $roles;
     }

     public static function users_with_group() {
 
        $select = array(
           't_user.*', 
           't_user_group.id as user_group_id', 
           't_user_group.name', 
           't_user_group.can_create', 
           't_user_group.can_read', 
           't_user_group.can_update', 
           't_user_group.can_delete' 
         );
 
        return Users::select($select)
          ->join('t_user_group', 't_user.user_group_id', '=', 't_user_group.id')
          ->whereRaw('t_user.deleted_at is null')
          ->whereRaw('t_user_group.deleted_at is null');
       }
}
