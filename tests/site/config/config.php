<?php

// load dotenv plugins class
require_once __DIR__ . '/../../../classes/DotEnv.php';

return [
    'no_callback' => \Bnomei\DotEnv::getenv(
        'KIRBY_API_USER',
        [
            'dir' => realpath(__DIR__ . '/../../'),
            'file' => '.env',
        ],
    ),
];
