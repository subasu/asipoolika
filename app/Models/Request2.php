<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected  $table='requests';
    //shiri relation user
    public function user()
    {
        return $this->blongsTo('App\User');
    }


    //shiri relation of form and request
    public function form()
    {
        return $this->hasOne('App\Models\Form');
    }

    //shiri relation of request and certificate
    public function certificate()
    {
        return $this->hasOne('App\Models\Certificate');
    }

    //shiri relation of request and request_type
    public function requestType()
    {
        return $this->hasOne('App\Models\RequestType');
    }

    //shiri relation of request and request_records
    public function requestRecord()
    {
        return $this->hasMany('App\Models\RequestRecord');
    }
}
