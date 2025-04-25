<?php

return [


    'default' => env('FILESYSTEM_DRIVER', 'local'),

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),


    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'local2' => [
            'driver' => 'local',
            'root' => env('FILESYSTEM_LOCAL2_ROOT'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'uploads' => [
            'driver' => 'local',
            'root' => public_path('uploads'), // previously storage_path();
            'url' => env('APP_URL'). '/uploads'
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        'ftp' => [
            'driver' => 'ftp',
            'host' => env('FILESYSTEM_FTP_HOST'),
            'username' => env('FILESYSTEM_FTP_USERNAME'),
            'password' => env('FILESYSTEM_FTP_PASSWORD'),
            'port'     => env('FILESYSTEM_FTP_PORT',21),
            'root'     => env('FILESYSTEM_FTP_ROOT','')
            // Optional FTP Settings...
            // 'port'     => 21,
            // 'root'     => '',
            // 'passive'  => true,
            // 'ssl'      => true,
            // 'timeout'  => 30,
        ],

    ],

    'root_url' => env('FILESYSTEM_ROOT_URL', null),

];
