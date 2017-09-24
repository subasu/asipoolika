<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workers extends Model
{
    //
    protected $table = 'workers';

    //shiri : this below function is related to relation of worker and user
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
