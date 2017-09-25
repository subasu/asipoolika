<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    //shiri : this function is related to relation of user and ticket
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // shiri : this function is related to relation of ticket and unit
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    //shiri : this function is related to relation of ticket and conversation
    public function conversation()
    {
        return $this->hasOne('App\Models\Conversation','ticket_id');
    }
}
