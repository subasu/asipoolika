<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'ticket_messages';
    //shiri : relation of ticket and message
    public function ticket()
    {
        return $this->belongsTo('App\Models\Ticket');
    }

}
