<?php

use Bnomei\DotEnv;
use Kirby\Toolkit\A;

@include_once __DIR__.'/vendor/autoload.php';
@include_once __DIR__.'/global.php';

Kirby::plugin('bnomei/dotenv', [
    'options' => [
        'dir' => function (): array {
            return [
                kirby()->roots()->index(), // default setup (like official starter-kit)
                realpath(kirby()->roots()->index().'/../'), // public/storage folder setup
            ];
        },
        'file' => '.env',
        'environment' => function (): string {
            $host = A::get($_ENV, 'KIRBY_HOST')
                ?? A::get($_SERVER, 'KIRBY_HOST')
                ?? getenv('KIRBY_HOST');

            return explode(':', strval($host ?: ''))[0];
        },
        'required' => [],
        'setup' => function ($dotenv) {
            // overwrite this callback if you want to do additional tasks
            // $dotenv->required('FOO');
            return $dotenv;
        },
    ],
    'pageMethods' => [
        'env' => function (string $env, mixed $default = null) {
            return DotEnv::getenv($env, $default);
        },
        'getenv' => function (string $env, mixed $default = null) {
            return DotEnv::getenv($env, $default);
        },
    ],
    'siteMethods' => [
        'env' => function (string $env, mixed $default = null) {
            return DotEnv::getenv($env, $default);
        },
        'getenv' => function (string $env, mixed $default = null) {
            return DotEnv::getenv($env, $default);
        },
        'loadenv' => function (array $options = []): bool {
            return DotEnv::load($options);
        },
    ],
]);
