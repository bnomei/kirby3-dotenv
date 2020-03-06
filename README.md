# Kirby 3 DotEnv

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-dotenv?color=ae81ff)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-dotenv?color=272822)
[![Build Status](https://flat.badgen.net/travis/bnomei/kirby3-dotenv)](https://travis-ci.com/bnomei/kirby3-dotenv)
[![Coverage Status](https://flat.badgen.net/coveralls/c/github/bnomei/kirby3-dotenv)](https://coveralls.io/github/bnomei/kirby3-dotenv) 
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-dotenv)](https://codeclimate.com/github/bnomei/kirby3-dotenv)  
[![Twitter](https://flat.badgen.net/badge/twitter/bnomei?color=66d9ef)](https://twitter.com/bnomei)

Kirby 3 Plugin for environment variables from .env

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
echo env('APP_DEBUG'); // true
```

### Using getenv() in the Kirby config file

**site/config/config.php**
```php
return [
    // ... other options
    'bnomei.cloudconvert.apikey' => function() { 
        return env('CLOUDCONVERT_APIKEY'); 
    },
    'bnomei.instagram.token' => function() { 
        return env('INSTAGRAM_TOKEN'); 
    },
    'bnomei.thumbimageoptim.apikey' => function() { 
        return env('IMAGEOPTIM_APIKEY'); 
    },
];
```

#### No callback - no luck? 3 line are enough!
Unless you preload the `Bnomei\DotEnv` class using an `include_once` statement yourself the class will not be available in the kirby config files. But some options take a `callback` not just a `string` value. If your desired option does not then consider reporting a github issue at **their** repository. Adding a callback for an option is 3 lines of work.

**code/in/another/plugin.php**
```php
public function thisIsWereAllConfigValuesAreLoaded()
{
    $fancyOption = option('another.plugin.fancy');
    // add these 3 lines
    if (is_callable($fancyOptions)) {
        $fancyOption = $fancyOption();
    }
}
```


## Settings

| bnomei.dotenv.            | Default        | Description               |            
|---------------------------|----------------|---------------------------|
| dir | `callback` | returning `kirby()->roots()->index(). When installing Kirby 3 with Composer use a `function() { return realpath(kirby()->roots()->index() . '/../'); }` | 
| filename | `.env` | |
| required | `[]` | You can define required variables in the Settings using an array. If any of these is missing a `RuntimeException` will be thrown. |

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
