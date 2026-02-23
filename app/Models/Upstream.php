<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upstream extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['peer_name', 'capacity', 'location_id', 'provider', 'asn'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function reports()
    {
        return $this->hasMany(UpstreamReport::class);
    }
}
