# Kirby 3 DotEnv

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-dotenv?color=ae81ff)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-dotenv?color=272822)
[![Build Status](https://flat.badgen.net/travis/bnomei/kirby3-dotenv)](https://travis-ci.com/bnomei/kirby3-dotenv)
[![Coverage Status](https://flat.badgen.net/coveralls/c/github/bnomei/kirby3-dotenv)](https://coveralls.io/github/bnomei/kirby3-dotenv) 
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-dotenv)](https://codeclimate.com/github/bnomei/kirby3-dotenv)  
[![Twitter](https://flat.badgen.net/badge/twitter/bnomei?color=66d9ef)](https://twitter.com/bnomei)

Kirby 3 Plugin for environment variables from .env

## Commercial Usage

> <br>
> <b>Support open source!</b><br><br>
> This plugin is free but if you use it in a commercial project please consider to sponsor me or make a donation.<br>
> If my work helped you to make some cash it seems fair to me that I might get a little reward as well, right?<br><br>
> Be kind. Share a little. Thanks.<br><br>
> &dash; Bruno<br>
> &nbsp; 

| M | O | N | E | Y |
|---|----|---|---|---|
| [Github sponsor](https://github.com/sponsors/bnomei) | [Patreon](https://patreon.com/bnomei) | [Buy Me a Coffee](https://buymeacoff.ee/bnomei) | [Paypal dontation](https://www.paypal.me/bnomei/15) | [Hire me](mailto:b@bnomei.com?subject=Kirby) |

## Similar Plugins

- [beebmx/kirby-env](https://github.com/beebmx/kirby-env)
- [johannschopplich/kirby-extended](https://github.com/johannschopplich/kirby-extended)

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-dotenv/archive/master.zip) as folder `site/plugins/kirby3-dotenv` or
- `git submodule add https://github.com/bnomei/kirby3-dotenv.git site/plugins/kirby3-dotenv` or
- `composer require bnomei/kirby3-dotenv`

## Setup

### .env file Examples
**/.env**
```dotenv
APP_MODE=production
APP_DEBUG=false
ALGOLIA_APIKEY=12d7331a21d8a28b3069c49830f463e833e30f6d
KIRBY_API_USER=bnomei
KIRBY_API_PW=52d3a0edcc78be6c5645fdb7568f94d3d83d1c2a
```

**/.env.staging**
```dotenv
APP_MODE=staging
APP_DEBUG=true
ALGOLIA_APIKEY=950306d052ec893b467f2ca088daf2964b9f9530
KIRBY_API_USER=notBnomei
KIRBY_API_PW=37e30ad867ff3a427317dcd1852abbd692b39ffc
```

## Usage everywhere but in Config files

> ⚠️ ATTENTION: The global PHP functions `getenv()` or `putenv()` are NOT supported by this plugin since v2. What will work...
> - use super globals `$_ENV[]`, `$_SERVER[]` or 
> - the plugins global helper function `env()` or
> - `->getenv()`, `->env()` page and site methods

**on server**
```php
echo $_ENV['APP_MODE']; // production
echo env('APP_DEBUG'); // false
// or
echo $page->getenv('ALGOLIA_APIKEY'); // 12d7331a21d8a28b3069c49830f463e833e30f6d
echo $page->env('ALGOLIA_APIKEY'); // 12d7331a21d8a28b3069c49830f463e833e30f6d
echo site()->getenv('ALGOLIA_APIKEY'); // 12d7331a21d8a28b3069c49830f463e833e30f6d
echo site()->env('ALGOLIA_APIKEY'); // 12d7331a21d8a28b3069c49830f463e833e30f6d
```

**on staging server**
```php
echo $_ENV['APP_MODE']; // staging
echo env('APP_DEBUG'); // true
// or
echo $page->getenv('ALGOLIA_APIKEY'); // 37e30ad867ff3a427317dcd1852abbd692b39ffc
echo $page->env('ALGOLIA_APIKEY'); // 37e30ad867ff3a427317dcd1852abbd692b39ffc
echo site()->getenv('ALGOLIA_APIKEY'); // 37e30ad867ff3a427317dcd1852abbd692b39ffc
echo site()->env('ALGOLIA_APIKEY'); // 37e30ad867ff3a427317dcd1852abbd692b39ffc
```

## Usage in Config files

See [config examples](https://github.com/bnomei/kirby3-dotenv/tree/master/tests/site/config) on how to use this plugin in combination with kirbys config files. Since v2 this plugin support Kirbys [Multi-environment setup](https://getkirby.com/docs/guide/configuration#multi-environment-setup) used to merging multiple config files.

## Default values

In case you want to provide a default value as fallback in case the environment variable is not set you can do that with the 2nd parameter in each helper function.

```php
 // `true` as default value
echo env('ALGOLIA_ENABLED', true);
```

> Thanks for your PR @teichsta

## Settings

| bnomei.dotenv.            | Default        | Description               |            
|---------------------------|----------------|---------------------------|
| dir | `callback` | returning `kirby()->roots()->index().` When installing Kirby 3 with Composer use a `function() { return realpath(kirby()->roots()->index() . '/../'); }` | 
| file | `.env` | |
| required | `[]` | You can define required variables in the Settings using an array. If any of these is missing a `RuntimeException` will be thrown. |
| setup | `callback` | perform additional tasks on raw dotenv class instance |

## Dependencies

- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-dotenv/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

## Credits

based on K2 version of
- https://github.com/jevets/kirby-phpdotenv
