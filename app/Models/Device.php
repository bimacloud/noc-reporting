<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'device_type_id',
        'brand',
        'model',
        'serial_number',
        'location_id',
        'status',
        'netbox_id',
        'ip_address',
        'role',
        'manufacturer',
        'platform',
        'site_id',
        'site_name',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function deviceReports()
    {
        return $this->hasMany(DeviceReport::class);
    }
}
