<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = ['direction', 'event', 'bat_id', 'status', 'attempts', 'payload', 'response'];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'response' => 'array',
        ];
    }
}
