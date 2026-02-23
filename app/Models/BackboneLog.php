<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackboneLog extends Model
{
    protected $fillable = [
        'backbone_link_id',
        'type',
        'old_capacity',
        'new_capacity',
        'request_date',
        'execute_date',
        'notes'
    ];

    public function backboneLink()
    {
        return $this->belongsTo(BackboneLink::class);
    }
}
