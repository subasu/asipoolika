<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{

    public function certificateRecords()
    {
        return $this->hasMany('App\Models\CertificateRecord','certificate_id');
    }

    //shiri relation of certificate and certificate_type
    public function certificateType()
    {
        return $this->hasOne('App\Models\CertificateType');
    }

    //shiri relation of certificate and signature
    public function signature()
    {
        return $this->hasMany('App\Models\Signature','signature_id');
    }

    //shiri relation of signature and certificate
    public function request()
    {
        return $this->belongsTo('App\Models\Request2');
    }




}
