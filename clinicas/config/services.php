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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'clave' => env('AWS_ACCESS_KEY_ID'),
        'secreto' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'clave' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notificaciones' => [
            'token_oauth_bot' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'canal' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Configuración de API de Google Maps
    'google_maps' => [
        'clave_api' => env('GOOGLE_MAPS_API_KEY'),
    ],

    // Configuración de API de OpenWeather
    'openweather' => [
        'clave_api' => env('OPENWEATHER_API_KEY'),
    ],

];
