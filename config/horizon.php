<?php

return [
    'domain' => null,
    'path' => 'horizon',
    'use' => 'default',
    'prefix' => env('HORIZON_PREFIX', 'subscribers_horizon:'),
    'middleware' => ['web', 'admin.auth'],
    'waits' => ['redis:subscribers' => 60],
    'trim' => [
        'recent' => 60,
        'pending' => 60,
        'completed' => 60,
        'recent_failed' => 10080,
        'failed' => 10080,
        'monitored' => 10080,
    ],
    'silenced' => [],
    'environments' => [
        'production' => [
            'supervisor-1' => [
                'maxProcesses' => 5,
                'balanceMaxShift' => 1,
                'balanceCooldown' => 3,
            ],
        ],
        'testing' => [
            'supervisor-1' => [
                'maxProcesses' => 3,
            ],
        ],
    ],
];
