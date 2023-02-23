<?php

@include_once __DIR__ . '/vendor/autoload.php';
@include_once __DIR__ . '/global.php';

Kirby::plugin('bnomei/dotenv', [
    'options' => [
        'dir' => function (): string {
            return kirby()->roots()->index();
        // return realpath(kirby()->roots()->index() . '/../'); // public folder setup
        },
        'file' => '.env',
        'required' => [],
        'setup' => function ($dotenv) {
            // overwrite to do additional tasks
            return $dotenv;
        }
    ],
    'pageMethods' => [
        'env' => function (string $env, mixed $default = null) {
            return \Bnomei\DotEnv::getenv($env, $default);
        },
        'getenv' => function (string $env, mixed $default = null) {
            return \Bnomei\DotEnv::getenv($env, $default);
        },
    ],
    'siteMethods' => [
        'env' => function (string $env, mixed $default = null) {
            return \Bnomei\DotEnv::getenv($env, $default);
        },
        'getenv' => function (string $env, mixed $default = null) {
            return \Bnomei\DotEnv::getenv($env, $default);
        },
    ],
]);
