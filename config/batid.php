<?php

return [
    'api_mode' => env('BAT_ID_API_MODE', 'mock'),
    'mock_phone' => env('BAT_ID_MOCK_PHONE', '+41792094478'),
    'mock_batid' => env('BAT_ID_MOCK_BATID', 'BAT-TEST-0001'),
    'outgoing_api_url' => env('BAT_ID_OUTGOING_API_URL', ''),
    'outgoing_api_key' => env('BAT_ID_OUTGOING_API_KEY', ''),
    'outgoing_timeout' => (int) env('BAT_ID_OUTGOING_TIMEOUT', 10),
    'outgoing_max_retries' => (int) env('BAT_ID_OUTGOING_MAX_RETRIES', 5),

    // Deeplink (URL parameters from bat-id app)
    'deeplink_secret' => env('DEEPLINK_SECRET', ''),
    'deeplink_ttl' => (int) env('DEEPLINK_TTL', 600), // seconds
];
