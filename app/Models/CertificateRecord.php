<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateRecord extends Model
{
    //shiri relation of certificate and certificate_records
    public function certificate()
    {
        return $this->blongsTo('App\Models\Certificate');
    }
}
