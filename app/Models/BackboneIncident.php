<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackboneIncident extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'backbone_link_id',
        'incident_date',
        'resolve_date',
        'latency',
        'down_status',
        'duration',
        'notes',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'resolve_date' => 'datetime',
        'down_status' => 'boolean',
    ];

    public function backboneLink()
    {
        return $this->belongsTo(BackboneLink::class);
    }
}
