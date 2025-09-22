<?php

return [
    'channels' => [
        'app' => [
            'type' => 'file',
            'path' => 'storage/logs/app.log',
        ],
        'admin' => [
            'type' => 'file',
            'path' => 'storage/logs/admin.log',
        ],
    ]
];