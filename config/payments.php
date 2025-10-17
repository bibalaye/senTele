<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration des paiements
    |--------------------------------------------------------------------------
    */

    'wave' => [
        'api_key' => env('WAVE_API_KEY'),
        'secret_key' => env('WAVE_SECRET_KEY'),
        'api_url' => env('WAVE_API_URL', 'https://api.wave.com/v1'),
        'callback_url' => env('APP_URL') . '/payments/wave/callback',
        'enabled' => env('WAVE_ENABLED', false),
    ],

    'orange_money' => [
        'merchant_key' => env('ORANGE_MERCHANT_KEY'),
        'merchant_id' => env('ORANGE_MERCHANT_ID'),
        'api_url' => env('ORANGE_API_URL', 'https://api.orange.com/orange-money-webpay/'),
        'callback_url' => env('APP_URL') . '/payments/orange/callback',
        'enabled' => env('ORANGE_MONEY_ENABLED', false),
    ],

    'free_money' => [
        'api_key' => env('FREE_MONEY_API_KEY'),
        'merchant_id' => env('FREE_MONEY_MERCHANT_ID'),
        'api_url' => env('FREE_MONEY_API_URL'),
        'callback_url' => env('APP_URL') . '/payments/free/callback',
        'enabled' => env('FREE_MONEY_ENABLED', false),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'enabled' => env('STRIPE_ENABLED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Devise par défaut
    |--------------------------------------------------------------------------
    */
    'default_currency' => env('PAYMENT_CURRENCY', 'XOF'),

    /*
    |--------------------------------------------------------------------------
    | Modes de paiement disponibles
    |--------------------------------------------------------------------------
    */
    'available_methods' => [
        'wave' => 'Wave',
        'orange_money' => 'Orange Money',
        'free_money' => 'Free Money',
        'stripe' => 'Carte bancaire',
    ],
];
