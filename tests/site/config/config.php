<?php

return [
    // config will use default settings to load the env file
    /*
    'bnomei.dotenv.dir' => function (): string {
        return kirby()->roots()->index();
        // return realpath(kirby()->roots()->index() . '/../'); // public folder setup
    },
    'bnomei.dotenv.file' => '.env',
    'bnomei.dotenv.required' => [],
    */
    'var_from_env' => function () {
        // you can only use getenv inside of closures unless you load the class beforehand. see staging example.
        return env('APP_MODE'); // "production" here but "staging" in staging
    },
];
