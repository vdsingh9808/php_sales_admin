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

    'token' => [
        'key' => env('TOKEN_KEY'),
    ],

    'seeder' => [
        'email' => env('SEEDER_EMAIL','eni@eni.com'),
        'password' => env('SEEDER_PASSWORD','password'),
        'name' => env('SEEDER_NAME','Admin')
    ],

    'server_url' => env('SERVER_URL','https://eni-sat.api.crm4.dynamics.com/api/data/v9.2/'),
    'authentication_url' => env('AUTHENTICATION_URL','https://prod-52.westeurope.logic.azure.com:443/workflows/6b19ea8852ff441abf02d035ac44e74f/triggers/manual/paths/invoke?api-version=2016-06-01&sp=%2Ftriggers%2Fmanual%2Frun&sv=1.0&sig=oeAX6cxyw3a2SJUQRAnQRrT6Z7jNSr3DPWynsIfebD0'),
    
    'application_name' => env('APPLICATION_NAME','ENI Portal'),

    'theme_menus' => env('THEME_MENUS') 
];
