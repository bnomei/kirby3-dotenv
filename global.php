<?php

@include_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/classes/DotEnv.php';

if (! function_exists('loadenv')) {
    function loadenv(array $options = [])
    {
        return \Bnomei\DotEnv::load($options);
    }
}

if (! function_exists('env')) {
    function env(string $env, mixed $default = null)
    {
        return \Bnomei\DotEnv::getenv($env, $default);
    }
}
