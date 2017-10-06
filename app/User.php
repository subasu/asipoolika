<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_role', 'user_id', 'role_id');
    }

    //doe's the user have any role?
    public function hasAnyRole($roles)
    {
        //contain the roles that i want to check for
        //check if $roles is an array because a user can have multiple roles ['admin','author'] or send only one in an array ['admin']
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role)
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit','unit_id');
    }


    //shiri relation of request_records  and users
    public function requestRecord()
    {
        return $this->hasMany('App\Models\RequestRecord','refuse_user_id');
    }

    //shiri relation of request and user
    public function request2()
    {
        return $this->hasMany('App\Models\Request2');
    }

    //rayat:This function use for manage users page
    public function user()
    {
        return $this->belongsTo('App\User','supervisor_id');
    }

//    public function user2()
//    {
//        return $this->belongsTo('App\User','unit_id');
//    }

    //shiri: this function is related to relation of user and worker
    public function worker()
    {
        return $this->hasMany('App\Models\Workers','user_id');
    }

    //shiri : this function is related to relation of user and ticket
    public function ticket()
    {
        return $this->hasMany('App\Models\Ticket');
    }

    //shiri : relation
}
