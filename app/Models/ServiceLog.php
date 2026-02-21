<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceLog extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'old_bandwidth',
        'new_bandwidth',
        'request_date',
        'execute_date',
        'notes',
    ];

    protected $casts = [
        'request_date' => 'date',
        'execute_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
