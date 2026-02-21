<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Assuming User model is in App\Models

class MonthlySummary extends Model
{
    protected $fillable = [
        'month',
        'total_incident',
        'total_downtime',
        'sla_percentage',
        'total_activation',
        'total_upgrade',
        'created_by',
    ];

    protected $casts = [
        'sla_percentage' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
