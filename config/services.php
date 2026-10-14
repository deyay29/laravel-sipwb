<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Open-Meteo (cuaca)
    |--------------------------------------------------------------------------
    |
    | Wilayah yang ditampilkan. Nama wilayah ("Bandung") diterjemahkan menjadi
    | koordinat lewat Geocoding API Open-Meteo, lalu disimpan di cache.
    | Koordinat di bawah hanya dipakai sebagai cadangan bila API gagal.
    |
    */

    'open_meteo' => [
        'region' => env('WEATHER_REGION', 'Bandung'),

        'latitude' => env('WEATHER_LATITUDE', -6.92222),
        'longitude' => env('WEATHER_LONGITUDE', 107.60694),
        'timezone' => env('WEATHER_TIMEZONE', 'Asia/Jakarta'),

        // Berapa lama hasil geocoding disimpan (menit).
        'geocode_cache_minutes' => 60 * 24,

        'forecast_url' => 'https://api.open-meteo.com/v1/forecast',
        'geocoding_url' => 'https://geocoding-api.open-meteo.com/v1/search',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
