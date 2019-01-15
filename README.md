# Kirby 3 DotEnv

![GitHub release](https://img.shields.io/github/release/bnomei/kirby3-dotenv.svg?maxAge=1800) ![License](https://img.shields.io/github/license/mashape/apistatus.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-3%2B-black.svg)

Kirby 3 Plugin for environment variables from .env using [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv).

## Commercial Usage

This plugin is free but if you use it in a commercial project please consider to
- [make a donation 🍻](https://www.paypal.me/bnomei/1) or
- [buy me ☕](https://buymeacoff.ee/bnomei) or
- [buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/35731?link=1170)

## Installation

- for devkit-setup use `composer require bnomei/kirby3-dotenv` or
- extract latest release of [kirby3-dotenv.zip](https://github.com/bnomei/kirby3-dotenv/releases/download/v1.0.5/kirby3-dotenv.zip) as folder `site/plugins/kirby3-dotenv`

> Installation as a gitsubmodule is *not* supported.


## Setup

**.env file**
```
APP_MODE=production
APP_DEBUG=true
ALGOLIA_APIKEY=12d7331a21d8a28b3069c49830f463e833e30f6d
KIRBY_API_USER=bnomei
KIRBY_API_PW=52d3a0edcc78be6c5645fdb7568f94d3d83d1c2a
```

**plugin helper methods**
```php
echo env('APP_MODE'); // production
// or
echo $page->getenv('ALGOLIA_APIKEY'); // 12d7331a21d8a28b3069c49830f463e833e30f6d
```

**plain php**
```php
Bnomei\Dotenv::load();
echo getenv('APP_DEBUG'); // true
```

## Settings

All settings need to be prefixed with `bnomei.dotenv.`.

**dir**
- default: a callback returning `kirby()->roots()->index()`

> TIP: for Kirby 3 Devit setup use `realpath(kirby()->roots()->index() . '/../');`

**filename**
- default: `.env`

**required**
- default: `[]`

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-dotenv/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

## Credits

based on K2 version of
- https://github.com/jevets/kirby-phpdotenv
