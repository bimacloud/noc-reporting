<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'netbox_id',
        'name',
        'slug',
        'status',
        'region',
        'site_group_id',
        'site_group_name',
        'address',
        'description',
        'device_count',
    ];

    /** Site Group induk */
    public function siteGroup()
    {
        return $this->belongsTo(SiteGroup::class, 'site_group_id');
    }

    /** Locations dalam site ini */
    public function locations()
    {
        return $this->hasMany(Location::class, 'site_id');
    }

    /** Devices dalam site ini */
    public function devices()
    {
        return $this->hasMany(Device::class, 'site_id');
    }
}
