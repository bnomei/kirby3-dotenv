# Kirby DotEnv

[![Kirby 5](https://flat.badgen.net/badge/Kirby/5?color=ECC748)](https://getkirby.com)
![PHP 8.2](https://flat.badgen.net/badge/PHP/8.2?color=4E5B93&icon=php&label)
![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-dotenv?color=ae81ff&icon=github&label)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-dotenv?color=272822&icon=github&label)
[![Coverage](https://flat.badgen.net/codeclimate/coverage/bnomei/kirby3-dotenv?icon=codeclimate&label)](https://codeclimate.com/github/bnomei/kirby3-dotenv)
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-dotenv?icon=codeclimate&label)](https://codeclimate.com/github/bnomei/kirby3-dotenv/issues)
[![Discord](https://flat.badgen.net/badge/discord/bnomei?color=7289da&icon=discord&label)](https://discordapp.com/users/bnomei)
[![Buymecoffee](https://flat.badgen.net/badge/icon/donate?icon=buymeacoffee&color=FF813F&label)](https://www.buymeacoffee.com/bnomei)

Kirby Plugin for environment variables from .env files

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-dotenv/archive/master.zip) as folder `site/plugins/kirby3-dotenv`
  or
- `git submodule add https://github.com/bnomei/kirby3-dotenv.git site/plugins/kirby3-dotenv` or
- `composer require bnomei/kirby3-dotenv`

## Usage

Create a `.env` file in the root of your Kirby installation. Within your Kirby project you can access the environment variables using various PHP helper functions.

### in production or with default environment

Based on an environment like
- `/site/config/config.php` Kirby configuration file and given a
- `/.env` file.

**/.env**

```dotenv
APP_MODE=production
APP_DEBUG=false
ALGOLIA_APIKEY=12d7331a21d8a28b3069c49830f463e833e30f6d
KIRBY_API_USER=bnomei
KIRBY_API_PW=52d3a0edcc78be6c5645fdb7568f94d3d83d1c2a
```

```php
<?php
echo $_ENV['APP_MODE']; // production
echo env('APP_DEBUG');  // false
// or
echo $page->getenv('ALGOLIA_APIKEY');  // 12d7331...
echo $page->env('ALGOLIA_APIKEY');     // 12d7331...
echo site()->getenv('ALGOLIA_APIKEY'); // 12d7331...
echo site()->env('ALGOLIA_APIKEY');    // 12d7331...
```

### on local or staging test server

You can also create files to have different settings for different environments.
The plugin will try to automatically load the correct file based on the environment.

Based on an environment like 
- http://dotenv.test matching a 
- `/site/config/config.dotenv.test.php` Kirby configuration file and given a 
- `/.env.dotenv.test` file.

**/.env.dotenv.test**
```dotenv
APP_MODE=staging
APP_DEBUG=true
ALGOLIA_APIKEY=950306d052ec893b467f2ca088daf2964b9f9530
KIRBY_API_USER=notBnomei
KIRBY_API_PW=37e30ad867ff3a427317dcd1852abbd692b39ffc
```

```php
<?php
echo $_ENV['APP_MODE']; // staging
echo env('APP_DEBUG');  // true
// ...
```

## Default values

In case you want to provide a default value as fallback in case the environment variable is not set you can do that with
the 2nd parameter in each helper function. Thanks for your PR @teichsta.

```php
<?php
// `true` as default value
echo env('ALGOLIA_ENABLED', true);
```

## Usage in Config files might require a manual load

The environment variables set by your hosting service are available in your PHP scripts by default using the `$_ENV` super-global and the `getenv()` function. They are injected into the PHP environment by the web server.

But the values from the `.env` files are NOT available in the same way. They need to be loaded manually. The Dotenv plugin will load these on it's first usage. But the plugin can only do so automatically once it has been loaded itself which is after the config files have been parsed by Kirby.

### Using Callbacks or the Ready Option

Where possible you should use the `callback` option to provide a function that returns the value you need. Once the callback is called the `.env` file will already have been loaded and the value will be available. Not all options support callbacks though.

**/site/config/config.php**
```php
<?php
return [
    // does not support callbacks
    // 'debug' => false, 

    // some plugins support callbacks for sensitive data options
    'bnomei.seobility.apikey' => function() { 
        return env('SEOBILITY_APIKEY'); 
    },
    
    // alternatively you can use the `ready` option
    // https://getkirby.com/docs/reference/system/options/ready
    'ready' => function() {
        return [
            'debug' => env('APP_DEBUG', false),
            'email' => [
                'transport' => [
                    'type' => 'smtp',
                    'host' => 'smtp.postmarkapp.com',
                    'port' => 587,
                    'security' => true,
                    'auth' => true,
                    'username' => env('POSTMARK_USERNAME'),
                    'password' => env('POSTMARK_PASSWORD'),
                ],
            ],
        ];
    }
];
```

### Manually load the .env file in the config file

If you still decide you need to use the values from the `.env` files in the config files of Kirby directly, you can manually initialize the loading of these like so:

**/site/config/config.php**
```php
<?php

require_once __DIR__ . '/../plugins/kirby3-dotenv/global.php';
loadenv();

return [
    'debug' => env('APP_DEBUG', false),
    //...
];
```

You can provide a custom options as an array to that function if you want to force loading a specific directory or file.

## Other Environment Variables Sources

If you set environment variables in your server configuration these will be available as well.

> [!WARNING]
> This plugin will load environment variables from `.env` files and potentially overwrite existing environment
> variables.

## In the CLI

When running Kirby commands in the CLI make sure you prefix the command with the environment variable for the HOST you want to use.

```sh
env KIRBY_HOST=dotenv.test kirby my:command
```

## Similar Plugins

- [beebmx/kirby-env](https://github.com/beebmx/kirby-env)
- [johannschopplich/kirby-extended](https://github.com/johannschopplich/kirby-extended)

## Settings

| bnomei.dotenv. | Default          | Description                                                                                                                                              |            
|----------------|------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------|
| dir            | `callback`       | returning `kirby()->roots()->index().` When installing Kirby 3 with Composer use a `function() { return realpath(kirby()->roots()->index() . '/../'); }` | 
| file           | `.env`           |                                                                                                                                                          |
| environment    | `callback`       | auto-detection for the current environment                                                                                                               |
| required       | `callback or []` | You can define required variables in the settings using an array. If any of these is missing a `RuntimeException` will be thrown.                        |
| setup          | `callback`       | perform additional tasks on raw dotenv class instance                                                                                                    |

## Dependencies

- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it
in a production environment. If you find any issues,
please [create a new issue](https://github.com/bnomei/kirby3-dotenv/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or
any other form of hate speech.

## Credits

based on K2 version of

- https://github.com/jevets/kirby-phpdotenv
