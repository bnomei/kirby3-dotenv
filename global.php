<?php

use Bnomei\DotEnv;

@include_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/classes/DotEnv.php';

if (! function_exists('loadenv')) {
    function loadenv(array $options = []): bool
    {
        return DotEnv::load($options, canUseKirbyOptions: false);
    }
}

if (! function_exists('env')) {
    function env(string $env, mixed $default = null): mixed
    {
        return DotEnv::getenv($env, $default);
    }
}
