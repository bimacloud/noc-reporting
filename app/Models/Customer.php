<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'service_type_id',
        'provider_id',
        'phone',
        'address',
        'registration_date',
        'device_type_id',
        'bandwidth',
        'status'
    ];

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function customerIncidents()
    {
        return $this->hasMany(CustomerIncident::class);
    }

    public function serviceLogs()
    {
        return $this->hasMany(ServiceLog::class);
    }
}
