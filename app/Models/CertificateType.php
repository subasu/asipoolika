<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateType extends Model
{
    //shiri relation of certificate and certificate_type

    public function certificate()
    {
        return $this->blongsTo('App\Models\Certificate');
    }
}
