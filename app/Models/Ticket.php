<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    //
    //shiri : this function is related to relation of user and ticket
    public function user()
    {
        return $this->belongsTo('App\User','sender_user_id');
    }

    // shiri : this function is related to relation of ticket and unit
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit','unit_id');
    }

    //shiri : this function is related to relation of ticket and conversation
    public function message()
    {
        return $this->hasMany('App\Models\Message','ticket_id');
    }
}
