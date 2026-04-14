<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderMetadata extends Model
{
    protected $table = 'order_metadata';

    protected $fillable = [
        'order_id', 'ip_address', 'user_agent', 'os', 'browser',
        'language', 'screen_resolution', 'timezone', 'referer',
        'session_id', 'server_timestamp',
    ];

    protected function casts(): array
    {
        return [
            'server_timestamp' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
