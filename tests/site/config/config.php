<?php

return [
    'var_from_env' => function () {
        // you can only use getenv inside of closures unless you load the class beforehand. see staging example.
        return env('APP_MODE'); // "production" here but "staging" in staging
    },
];
