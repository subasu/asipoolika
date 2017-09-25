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
        return $this->blongsTo('App\Models\Organization');
    }

    //shiri : relation of unit and ticket
    public function ticket()
    {
        return $this->hasMany('App\Models\Ticket','unit_id');
    }
}
