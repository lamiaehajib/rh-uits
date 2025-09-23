<?php

return [

    // ... (other configurations above)

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        // === Place your new disk configuration here ===
        'task_audios' => [
            'driver' => 'local',
            'root' => storage_path('app/public/task_audios'), // This will store files in storage/app/public/task_audios
            'url' => env('APP_URL').'/storage/task_audios',   // This makes them accessible via URL
            'visibility' => 'public',
        ],
        // ===============================================

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

        'google' => [
            'driver' => 'google',
            'clientId' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refreshToken' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),

            // Use the Folder ID of the subfolder directly
            'folderId' => '1qhAaXQGam_pT_oAyX4-rsDN_wjXEbZc7',

            // Remove the 'folder' and 'root' keys to avoid conflicts
            'root' => '',
            'folder' => '',
        ],

       ],

      'links' => [
        public_path('storage') => storage_path('app/public'),
      ],

];