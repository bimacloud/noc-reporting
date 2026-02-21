<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpstreamReport extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'upstream_id',
        'month',
        'status_l1',
        'status_l2',
        'status_l3',
        'advertise',
        'duration',
        'notes',
    ];

    protected $casts = [
        'month' => 'date',
    ];

    public function upstream()
    {
        return $this->belongsTo(Upstream::class);
    }
}
