<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $fillable = [
        'suite', 'group', 'test_name', 'status', 'comment',
        'last_run_at', 'next_run_at', 'error_message',
    ];

    protected function casts(): array
    {
        return [
            'last_run_at' => 'datetime',
            'next_run_at' => 'datetime',
        ];
    }
}
