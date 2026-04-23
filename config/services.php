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

    'ghn' => [
        'base_url' => env('GHN_API_URL', 'https://dev-online.ghn.vn'),
        'token' => env('GHN_API_TOKEN'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
    ],

    'momo' => [
        'endpoint'     => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
        'partner_code' => env('MOMO_PARTNER_CODE', 'MOMO'),
        'access_key'   => env('MOMO_ACCESS_KEY', 'F8BBA842ECF85'),
        'secret_key'   => env('MOMO_SECRET_KEY', 'K951B6PE1waDMi640xX08PD3vg6EkVlz'),
        'redirect_url' => env('MOMO_REDIRECT_URL'),
        'ipn_url'      => env('MOMO_IPN_URL'),
        'store_id'     => env('MOMO_STORE_ID', 'VenShop'),
        'request_type' => env('MOMO_REQUEST_TYPE', 'captureWallet'),
    ],

];
