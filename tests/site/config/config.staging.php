<?php

// load dotenv plugins class so getenv can be used outside of closures
require_once __DIR__ . '/../../../global.php';
// require_once __DIR__ . '/../plugins/kirby3-dotenv/global.php';

loadenv([
    'dir' => realpath(__DIR__ . '/../../'),
    'file' => '.env.staging',
]);

return [
    'no_callback' => env('KIRBY_API_USER'), // => notBnomei
];
