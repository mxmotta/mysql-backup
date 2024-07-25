<?php

return [
    'backup' => [
        'dir' => env('BACKUP_DIR', 'backup'),
        'destiny' => env('BACKUP_DESTINY', 'local'), // Avaliable destinies ['local', 'aws']
        'max_files' => env('BACKUP_MAXFILES', 5),
        'time' => env('BACKUP_TIME', '00:00')
    ],
    'log' => [
        'path' => 'logs'
    ]
];
