# Kirby 3 DotEnv

![GitHub release](https://img.shields.io/github/release/bnomei/kirby3-dotenv.svg?maxAge=1800) ![License](https://img.shields.io/github/license/mashape/apistatus.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-3%2B-black.svg) ![Kirby 3 Pluginkit](https://img.shields.io/badge/Pluginkit-YES-cca000.svg) [![Build Status](https://travis-ci.com/bnomei/kirby3-dotenv.svg?branch=master)](https://travis-ci.com/bnomei/kirby3-dotenv) [![Coverage Status](https://coveralls.io/repos/github/bnomei/kirby3-dotenv/badge.svg?branch=master)](https://coveralls.io/github/bnomei/kirby3-dotenv?branch=master) [![Gitter](https://badges.gitter.im/bnomei-kirby-3-plugins/community.svg)](https://gitter.im/bnomei-kirby-3-plugins/community?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

Kirby 3 Plugin for environment variables from .env using [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv).

## Commercial Usage

This plugin is free but if you use it in a commercial project please consider to
- [make a donation 🍻](https://www.paypal.me/bnomei/1) or
- [buy me ☕](https://buymeacoff.ee/bnomei) or
- [buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/35731?link=1170)

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-dotenv/archive/master.zip) as folder `site/plugins/kirby3-dotenv` or
- `git submodule add https://github.com/bnomei/kirby3-dotenv.git site/plugins/kirby3-dotenv` or
- `composer require bnomei/kirby3-dotenv`

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
Bnomei\DotEnv::load();
echo getenv('APP_DEBUG'); // true
```

### Required Variables (optional)

You can define required variables in the Settings using an array. If any of these is missing a `RuntimeException` will be thrown.

## Settings

All settings need to be prefixed with `bnomei.dotenv.`.

**dir**
- default: a callback returning `kirby()->roots()->index()`

> TIP: when installing Kirby 3 with Composer use a `function() { return realpath(kirby()->roots()->index() . '/../'); }`

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
