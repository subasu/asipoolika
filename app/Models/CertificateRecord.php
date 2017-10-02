<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateRecord extends Model
{
    public function certificate()
    {
        return $this->belongsTo('App\Models\Certificate');
    }
}
