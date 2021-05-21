<?php

@include_once __DIR__ . '/vendor/autoload.php';

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
        'getenv' => function (string $env) {
            return \Bnomei\DotEnv::getenv($env);
        },
    ],
]);

if (! function_exists('loadenv')) {
    function loadenv(array $options = [])
    {
        return \Bnomei\DotEnv::load($options);
    }
}

if (! function_exists('env')) {
    function env(string $env)
    {
        return \Bnomei\DotEnv::getenv($env);
    }
}
