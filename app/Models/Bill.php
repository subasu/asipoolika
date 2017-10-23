<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function request()
    {
        return $this->belongsTo('App\Models\Request2','request_id');
    }
}
