<?php

return [
    'default_disk' => env('FILESYSTEM_DISK', 'public'),

    'types' => [
        'avatar' => [
            'disk' => env('FILESYSTEM_DISK', 'public'),
            'directory' => 'avatars',
            'mimes' => ['image/jpeg', 'image/png', 'image/webp'],
            'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
            'max' => 2048,
            'visibility' => 'public',
        ],
        'product_image' => [
            'disk' => env('FILESYSTEM_DISK', 'public'),
            'directory' => 'products',
            'mimes' => ['image/jpeg', 'image/png', 'image/webp'],
            'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
            'max' => 4096,
            'visibility' => 'public',
        ],
        'message_image' => [
            'disk' => env('FILESYSTEM_DISK', 'public'),
            'directory' => 'messages',
            'mimes' => ['image/jpeg', 'image/png', 'image/webp'],
            'extensions' => ['jpg', 'jpeg', 'png', 'webp'],
            'max' => 4096,
            'visibility' => 'public',
        ],
    ],
];
