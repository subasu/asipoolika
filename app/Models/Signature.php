<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    //
    public function requestRecord()
    {
        return $this->hasMany('App\Models\RequestRecord', 'request_record_id');
    }


    //shiri relation of certificate and relation
    public function certificate()
    {
        return $this->hasMany('App\Models\Certificate' , 'certificate_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}