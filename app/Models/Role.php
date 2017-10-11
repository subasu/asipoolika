<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'user_role', 'role_id', 'user_id');
    }
}
