<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    //

    public function user()
    {
        return $this->hasMany('App\User');
    }


    //shiri relation of organization and units
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    //shiri : relation of unit and ticket
    public function ticket()
    {
        return $this->hasMany('App\Models\Ticket','unit_id');
    }

    //shiri : below function is related to relation signature and unit
    public function signature()
    {
        return $this->hasMany('App\Models\Signature','unit_id');
    }
}
