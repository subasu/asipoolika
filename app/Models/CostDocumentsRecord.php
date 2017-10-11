<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostDocumentsRecord extends Model
{
    //
    protected $table = 'cost_document_records';
    public function costDocument()
    {
        return $this->belongsTo('App\Models\CostDocument','cost_document_id');
    }
}
