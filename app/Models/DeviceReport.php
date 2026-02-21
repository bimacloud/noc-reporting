<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceReport extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'device_id',
        'month',
        'physical_status',
        'psu_status',
        'fan_status',
        'layer2_status',
        'layer3_status',
        'cpu_status',
        'throughput_in',
        'throughput_out',
        'duration_downtime',
        'notes',
    ];

    protected $casts = [
        'month' => 'date',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
