<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'conversation';
    //shiri : this function is related to relation of conversation and ticket
    public function ticket()
    {
        return $this->belongsTo('App\Models\Ticket');
    }

    //shiri : relation of conversation and message
    public function message()
    {
        return $this->hasMany('App\Models\Message');
    }

}
