<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackboneLink extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['node_a', 'node_b', 'provider', 'media', 'capacity'];

    public function incidents()
    {
        return $this->hasMany(BackboneIncident::class);
    }
}
