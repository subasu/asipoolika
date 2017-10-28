<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected  $table = 'warehouse';
    public function request()
    {
        return $this->belongsTo('App\Models\Request2','request_id');
    }
}
