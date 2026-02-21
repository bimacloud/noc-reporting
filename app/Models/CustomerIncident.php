<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerIncident extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'customer_id',
        'incident_date',
        'physical_issue',
        'backbone_issue',
        'layer_issue',
        'duration',
        'root_cause',
        'status',
        'notes',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'physical_issue' => 'boolean',
        'backbone_issue' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
