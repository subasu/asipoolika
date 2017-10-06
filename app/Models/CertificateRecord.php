<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateRecord extends Model
{
    public function certificate()
    {
        return $this->belongsTo('App\Models\Certificate');
    }
    public function requestRecord()
    {
        return $this->belongsTo('App\Models\RequestRecord','request_record_id');
    }

    //shiri : relation of user and certificate_records
    public function user()
    {
        return $this->belongsTo('App\User','receiver_id');
    }
}
