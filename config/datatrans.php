<?php

return [
    'env' => env('DATATRANS_ENV', 'sandbox'),
    'api_url' => env('DATATRANS_API_URL', 'https://api.sandbox.datatrans.com'),
    'merchant_id' => env('DATATRANS_MERCHANT_ID', ''),
    'sign_key' => env('DATATRANS_SIGN_KEY', ''),
    'allowed_methods' => ['VIS', 'ECA', 'TWI'],
    'currency' => 'CHF',
];
