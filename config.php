<?php

Kirby::plugin('bnomei/dotenv', [
    'options' => [
        'dir' => function (): string {
            return kirby()->roots()->index();
        // return realpath(kirby()->roots()->index() . '/../');
        },
        'filename' => '.env',
        'required' => []
    ],
    'pageMethods' => [
        'getenv' => function (string $env) {
            return \Bnomei\DotEnv::getenv($env);
        }
    ]
]);

if (!class_exists('Bnomei\DotEnv')) {
    require_once __DIR__ . '/classes/dotenv.php';
}

if (!function_exists('env')) {
    function env(string $env)
    {
        return \Bnomei\DotEnv::getenv($env);
    }
}
