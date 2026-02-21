<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetboxSyncLog extends Model
{
    protected $table = 'netbox_sync_logs';

    protected $fillable = [
        'entity_type',
        'netbox_id',
        'entity_name',
        'status',
        'message',
        'payload',
        'synced_at',
    ];

    protected $casts = [
        'payload'   => 'array',
        'synced_at' => 'datetime',
    ];

    public function isError(): bool
    {
        return $this->status === 'error';
    }

    public function isOk(): bool
    {
        return $this->status === 'ok';
    }
}
