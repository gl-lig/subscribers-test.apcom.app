<?php

return [
    'name' => env('APP_NAME', 'bat-id Subscribers'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'https://subscribers.bat-i.ch'),
    'timezone' => env('APP_TIMEZONE', 'Europe/Zurich'),
    'locale' => env('APP_LOCALE', 'fr'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'fr'),
    'faker_locale' => 'fr_CH',
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(explode(',', env('APP_PREVIOUS_KEYS', ''))),
    ],
    'maintenance' => [
        'driver' => 'file',
    ],
    'available_locales' => ['fr', 'de', 'it', 'en'],
];
