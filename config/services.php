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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => '1079359168744-ehm0qmfgmg4imvbk33n7d97t69sfjb87.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'http://localhost:99/api/infor-user/authorized/google/callback',
    ],

    'github' => [
        'client_id' => 'b1b1f3e7c5765cfd3510',
        'client_secret' => '48474a596ae636bba226c9b0f704221f2fcc3bb7',
        'redirect' => 'http://localhost:8000/api/user/authorized/github/callback',
    ],
];
