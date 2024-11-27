<?php

// env KIRBY_HOST=dotenv.test kirby get-mode
// env KIRBY_HOST=dotex.test kirby get-mode
return [
    'description' => 'get APP_MODE',
    'args' => [],
    'command' => static function (\Kirby\CLI\CLI $cli): void {

        $cli->out($cli->kirby()->environment()->host());

        // in CLI its necessary to load the env like this
        // if you want to use the global env() function
        $cli->kirby()->site()->loadenv();
        $cli->success(env('APP_MODE', 'unknown'));

        // using the page method would work directly
        $cli->success($cli->kirby()->page('home')->env('APP_MODE', 'unknown'));
    },
];
