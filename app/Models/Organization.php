<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    //
    public function city()
    {
        $this->blongsTo('App\Models\City');
    }


    //shiri relation of organization and units

    public function unit()
    {
        return $this->belongsToMany('App\Models\Unit');
    }
    public function user()
    {
        return $this->hasMany('App\User');
    }
}
