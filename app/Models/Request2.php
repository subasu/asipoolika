<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Request2 extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected  $table='requests';
    //shiri relation user
    public function user()
    {
        return $this->belongsTo('App\User');
    }


    //shiri relation of form and request
    public function form()
    {
        return $this->hasOne('App\Models\Form');
    }

    //shiri relation of request and certificate
    public function certificate()
    {
        return $this->hasMany('App\Models\Certificate','request_id');
    }

    //shiri relation of request and request_type
    public function requestType()
    {
        return $this->belongsTo('App\Models\RequestType');
    }

    //shiri relation of request and request_records
    public function requestRecord()
    {
        return $this->hasMany('App\Models\RequestRecord','request_id');
    }

    //shiri : relation of request and unit
    public function unit()
    {
        return $this->belongsTo('App\Models\Unit','unit_id');
    }

    //shiri : relatiaon of supplier
    public function supplier()
    {
        return $this->belongsTo('App\User','supplier_id');
    }
    public function bills()
    {
        return $this->hasMany('App\Models\Bill','request_id');
    }
    public function costDocument()
    {
        return $this->hasOne('App\Models\CostDocument','request_id');
    }
    public function warehouse()
    {
        return $this->hasOne('App\Models\Warehouse','request_id');
    }
}
