<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    //shiri relation of request and request_type
    public function request()
    {
        return $this->blongsTo('App\Models\Request');
    }
}
