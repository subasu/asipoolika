<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    public function organization()
    {
        $this->hasMany('App\Models|City');
    }
}
