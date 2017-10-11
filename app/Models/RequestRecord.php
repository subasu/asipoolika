<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestRecord extends Model
{
    //
    public function signature()
    {
        return $this->hasMany('App\Models\Signature', 'signature_id');
    }

    //shiri relation of request_record and request
    public function request()
    {
        return $this->belongsTo('App\Models\Request2','request_id');
    }
    public function certificate()
    {
        return $this->hasOne('App\Models\Certificate');
    }

    //shiri : below function is related to relation of certificate_record  and request_record
    public function certificateRecord()
    {
        return $this->hasOne('App\Models\CertificateRecord','request_record_id');
    }

    //shiri : relation of request_record and certificate_record

}
