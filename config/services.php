<?php

return [
    'postmark' => ['token' => env('POSTMARK_TOKEN')],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', 'placeholder-google-client-id'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', 'placeholder-google-client-secret'),
        'redirect' => env('APP_URL').'/auth/google/callback',
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID', 'placeholder-facebook-client-id'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', 'placeholder-facebook-client-secret'),
        'redirect' => env('APP_URL').'/auth/facebook/callback',
    ],
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'resend' => ['key' => env('RESEND_KEY')],
    'slack' => ['notifications' => [
        'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
        'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
    ]],
    'payment' => ['default' => env('DEFAULT_PAYMENT_PROVIDER', 'vnpay')],
    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE', ''), 'hash_secret' => env('VNPAY_HASH_SECRET', ''),
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_RETURN_URL', '/payment/callback/vnpay'),
    ],
    'momo' => [
        'partner_code' => env('MOMO_PARTNER_CODE', ''), 'access_key' => env('MOMO_ACCESS_KEY', ''),
        'secret_key' => env('MOMO_SECRET_KEY', ''),
        'endpoint' => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
    ],
    'sepay' => [
        'merchant_id' => env('SEPAY_MERCHANT_ID', ''), 'api_key' => env('SEPAY_API_KEY', ''),
        'endpoint' => env('SEPAY_ENDPOINT', 'https://my.sepay.vn/'),
    ],
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID', ''), 'client_secret' => env('PAYPAL_CLIENT_SECRET', ''),
        'mode' => env('PAYPAL_MODE', 'sandbox'),
    ],
];
