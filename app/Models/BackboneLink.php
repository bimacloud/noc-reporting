<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackboneLink extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['node_a', 'node_b', 'node_c', 'node_d', 'node_e', 'provider', 'media', 'capacity'];

    public function incidents()
    {
        return $this->hasMany(BackboneIncident::class);
    }
    
    public function logs()
    {
        return $this->hasMany(BackboneLog::class);
    }
}
