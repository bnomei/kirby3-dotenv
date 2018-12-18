<?php

namespace Bnomei;

class DotEnv
{
    private static $envLoaded = false;
    public static function load()
    {
        if (!static::$envLoaded) {
            $dir = option('bnomei.dotenv.dir', kirby()->roots()->index());
            if ($dir && is_callable($dir)) {
                $dir = $dir();
            }
            $dotenv = new \Dotenv\Dotenv($dir);
            $dotenv->load();
            $require = \option('bnomei.dotenv.required');
            if ($require && is_array($require) && count($require) > 0) {
                $dotenv->required($require);
                // TODO: type check
            }
        }
    }

    public static function getenv(string $env)
    {
        static::load();
        return \getenv($env);
    }
}
