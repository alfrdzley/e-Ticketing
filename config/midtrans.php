<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Midtrans Payment Gateway integration.
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

    /*
    |--------------------------------------------------------------------------
    | Notification URL
    |--------------------------------------------------------------------------
    |
    | URL untuk menerima notification dari Midtrans
    |
    */
    'notification_url' => env('APP_URL').'/payment/notification',
    'finish_url' => env('APP_URL').'/payment/finish',
    'unfinish_url' => env('APP_URL').'/payment/unfinish',
    'error_url' => env('APP_URL').'/payment/error',

    /*
    |--------------------------------------------------------------------------
    | Enabled Payment Methods
    |--------------------------------------------------------------------------
    |
    | Metode pembayaran yang akan diaktifkan
    |
    */
    'enabled_payments' => [
        'credit_card',
        'bank_transfer',
        'echannel',
        'bca_va',
        'bni_va',
        'bri_va',
        'other_va',
        'gopay',
        'ovo',
        'dana',
        'shopeepay',
        'indomaret',
        'alfamart',
    ],

    /*
    |--------------------------------------------------------------------------
    | Credit Card Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi khusus untuk pembayaran credit card
    |
    */
    'credit_card' => [
        'secure' => true,
        'bank' => 'bca', // Default acquiring bank
        'installment' => [
            'required' => false,
            'terms' => [
                'bni' => [3, 6, 12],
                'mandiri' => [3, 6, 12],
                'cimb' => [3],
                'bca' => [3, 6, 12],
                'offline' => [6, 12],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Expiry
    |--------------------------------------------------------------------------
    |
    | Waktu kadaluarsa pembayaran (dalam menit)
    |
    */
    'custom_expiry' => [
        'unit' => 'minute',
        'duration' => env('MIDTRANS_EXPIRY_DURATION', 60), // 1 hour default
    ],
];
