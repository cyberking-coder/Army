<?php

return [
    'postmark' => ['token' => env('POSTMARK_TOKEN')],
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'resend' => ['key' => env('RESEND_API_KEY')],
    'slack' => ['notifications' => ['bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'), 'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL')]],
    'ccavenue' => [
        'merchant_id' => env('CCAVENUE_MERCHANT_ID'),
        'access_code' => env('CCAVENUE_ACCESS_CODE'),
        'working_key' => env('CCAVENUE_WORKING_KEY'),
        // Test: https://test.ccavenue.com    Live: https://secure.ccavenue.com
        'base_url'    => env('CCAVENUE_BASE_URL', 'https://test.ccavenue.com'),
    ],
];
