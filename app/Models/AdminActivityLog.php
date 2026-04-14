<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivityLog extends Model
{
    protected $fillable = ['admin_id', 'action', 'module', 'data_before', 'data_after', 'ip_address'];

    protected function casts(): array
    {
        return [
            'data_before' => 'array',
            'data_after' => 'array',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
