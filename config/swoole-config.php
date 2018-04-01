<?php
return [
    'port' => '9501',
    'ip' => '0.0.0.0',
    'set' => [
        'daemonize' => env('DEAMONIZE',true),
        'dispatch_mode' => 5
    ]
];