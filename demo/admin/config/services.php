<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional file to locate the various service credentials.
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Read by App\Traits\UploadsToSupabase to build public image URLs.
    // The disk credentials themselves live in config/filesystems.php
    // (disks.supabase), pulled from the same .env values.
    'supabase' => [
        'url' => env('SUPABASE_URL'),
        'bucket' => env('SUPABASE_STORAGE_BUCKET'),
    ],

];
