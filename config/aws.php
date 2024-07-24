<?php

return [
    'version' => env('AWS_VERSION', 'latest'),
    'region'  => env('AWS_REGION', 'us-west-2'),
    'bucket'  => env('AWS_BUCKET', 'my-bucket'),
    'key'     => env('AWS_KEY', NULL),
    'secret'  => env('AWS_SECRET', NULL),
    'ACL'     => env('AWS_ACL', 'public-read'),
    'endpoint'     => env('AWS_ENDPOINT', NULL),
];
