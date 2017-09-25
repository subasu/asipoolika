<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //shiri : relation of conversation and message
    public function conversation()
    {
        return $this->belongsTo('App\Models\Conversation');
    }

}
