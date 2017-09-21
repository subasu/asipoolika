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
        return $this->blongsTo('App\Models\Request');
    }
}
