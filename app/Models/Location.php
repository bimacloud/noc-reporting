<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'type',
        'description',
        'status',
        'tenant',
        'device_count',
        'netbox_id',
        'site_id',
        'site_name',
    ];

    /** Site tempat lokasi ini berada */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function upstreams()
    {
        return $this->hasMany(Upstream::class);
    }
}

