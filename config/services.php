<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'telegram' => [
        'error_bot_token'=> env('TELEGRAM_ERROR_BOT_TOKEN','7824634244:AAFDsz67YHCM-AljJtLb8NA6pApUH0kDDgY'),
        'error_chat_id' => env('TELEGRAM_ERROR_CHAT_ID','-4700620232'),
    ],

    'telegram-bot-api' => [
        'token' => env('TELEGRAM_BOT_TOKEN', 'YOUR BOT TOKEN HERE'),
        'chat_id' => env('TELEGRAM_CHAT_ID', 'YOUR CHAT ID HERE'),
    ],

    'settings' => [
        'PREVENT_DB_FRESH' => env('PREVENT_DB_FRESH', false),
        'DB_LISTENING' => env('DB_LISTENING', false),
    ],

    'map_prices' => [
        'vat_percent' => env('VAT_PERCENT', 10),
    ],

    'aba_payment' => [
        'merchant_id' => env('ABA_MERCHANT_ID'),
        'api_key' => env('ABA_MERCHANT_API_KEY'),
        'payway_api_url' => env('ABA_MERCHANT_PAYWAY_API_URL'),
        'qr_api' => env('ABA_QR_API'),
        'check-transaction-api' => env('ABA_CHECK_TRANSACTION_API'),
        'check-transaction-api-2' => env('ABA_CHECK_TRANSACTION_API_2') 
    ],

];
