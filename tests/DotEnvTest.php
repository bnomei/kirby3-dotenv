<?php

use Bnomei\DotEnv;
use Kirby\Http\Remote;

require_once __DIR__.'/../vendor/autoload.php';
beforeEach(function () {
    if (! file_exists(__DIR__.'/.env')) {
        copy(
            __DIR__.'/.env.example',
            __DIR__.'/.env'
        );
    }
});
function removeDotEnvFile(): void
{
    $dotenv = __DIR__.'/.env';
    if (file_exists($dotenv)) {
        unlink($dotenv);
    }
}
// defaults are needed for tests as it does
// not have the context of the plugin with
// the kirby instance and option() helper
function defaults(): array
{
    return [
        'dir' => __DIR__,
        'file' => '.env',
    ];
}
function rememberSuperglobalValue(array $source, string $key): array
{
    return [array_key_exists($key, $source), $source[$key] ?? null];
}
function restoreSuperglobalValue(array &$source, string $key, array $state): void
{
    [$hadValue, $value] = $state;

    if ($hadValue) {
        $source[$key] = $value;
    } else {
        unset($source[$key]);
    }
}

test('construct', function () {
    $dotenv = new DotEnv(defaults());
    expect($dotenv)->toBeInstanceOf(DotEnv::class);
});
test('load', function () {
    $dotenv = new DotEnv(defaults());
    expect($dotenv->isLoaded())->toBeTrue();
});
test('required', function () {
    $dotenv = new DotEnv(defaults() + [
        'required' => ['APP_MODE'],
    ]);
    expect($dotenv->isLoaded())->toBeTrue();

    $this->expectExceptionMessageMatches('/(One or more environment variables failed assertions: DATABASE_DSN is missing)/');
    $dotenv = new DotEnv(defaults() + [
        'required' => ['DATABASE_DSN'],
    ]);
});
test('static load', function () {
    expect(DotEnv::load(defaults()))->toBeTrue();
});
test('getenv', function () {
    DotEnv::load(defaults());
    $user = DotEnv::getenv('KIRBY_API_USER');
    expect($user)->toEqual('bnomei');
});
test('getenv default value', function () {
    DotEnv::load(defaults());
    $user = DotEnv::getenv('NOT_IN_ENV', 'bnomei');
    expect($user)->toEqual('bnomei');
});

test('static load staging', function () {
    // regular before
    expect(DotEnv::load(defaults()))->toBeTrue();
    $user = DotEnv::getenv('KIRBY_API_USER');
    expect($user)->toEqual('bnomei');

    // staging in between
    expect(DotEnv::load([
        'dir' => __DIR__,
        'file' => '.env.dotenv.test',
    ]))->toBeTrue();

    $user = DotEnv::getenv('KIRBY_API_USER');
    expect($user)->toEqual('notBnomei');

    $user = $_ENV['KIRBY_API_USER'];
    expect($user)->toEqual('notBnomei');

    // regular again
    expect(DotEnv::load(defaults()))->toBeTrue();
    $user = DotEnv::getenv('KIRBY_API_USER');
    expect($user)->toEqual('bnomei');
});
test('default environment ignores request host', function () {
    $httpHost = rememberSuperglobalValue($_SERVER, 'HTTP_HOST');
    $kirbyHost = rememberSuperglobalValue($_ENV, 'KIRBY_HOST');
    $serverKirbyHost = rememberSuperglobalValue($_SERVER, 'KIRBY_HOST');

    try {
        unset($_ENV['KIRBY_HOST']);
        unset($_SERVER['KIRBY_HOST']);
        $_SERVER['HTTP_HOST'] = 'dotenv.test';

        expect(DotEnv::load(defaults() + [
            'environment' => kirby()->option('bnomei.dotenv.environment'),
        ]))->toBeTrue();

        expect(DotEnv::getenv('APP_MODE'))->toEqual('production');
    } finally {
        restoreSuperglobalValue($_SERVER, 'HTTP_HOST', $httpHost);
        restoreSuperglobalValue($_ENV, 'KIRBY_HOST', $kirbyHost);
        restoreSuperglobalValue($_SERVER, 'KIRBY_HOST', $serverKirbyHost);
    }
});
test('default environment accepts explicit KIRBY_HOST', function () {
    $httpHost = rememberSuperglobalValue($_SERVER, 'HTTP_HOST');
    $kirbyHost = rememberSuperglobalValue($_ENV, 'KIRBY_HOST');
    $serverKirbyHost = rememberSuperglobalValue($_SERVER, 'KIRBY_HOST');

    try {
        unset($_ENV['KIRBY_HOST']);
        $_SERVER['KIRBY_HOST'] = 'dotenv.test';
        $_SERVER['HTTP_HOST'] = 'dotex.test';

        expect(DotEnv::load(defaults() + [
            'environment' => kirby()->option('bnomei.dotenv.environment'),
        ]))->toBeTrue();

        expect(DotEnv::getenv('APP_MODE'))->toEqual('staging');
    } finally {
        restoreSuperglobalValue($_SERVER, 'HTTP_HOST', $httpHost);
        restoreSuperglobalValue($_ENV, 'KIRBY_HOST', $kirbyHost);
        restoreSuperglobalValue($_SERVER, 'KIRBY_HOST', $serverKirbyHost);
    }
});
test('loaded to page', function () {
    // Bnomei\DotEnv::load(defaults());
    $response = kirby()->render('/');
    expect($response->code() === 200)->toBeTrue();
    expect($response->body())->toMatch('/(production)/');
});
test('loaded from config', function () {
    // Bnomei\DotEnv::load(defaults());
    $callback = kirby()->option('var_from_env');
    // => a closure
    expect($callback())->toEqual('production');
});
test('loaded from config ignores request host environment selection', function () {
    $response = Remote::get('http://dotenv.test');
    expect($response->code() === 200)->toBeTrue()
        ->and($response->content())->toEqual('production');

    $response = Remote::get('http://dotex.test');
    expect($response->code() === 200)->toBeTrue()
        ->and($response->content())->toEqual('production');
})->skipOnLinux();
test('load fails if missing file', function () {
    removeDotEnvFile();

    $dotenv = new DotEnv(defaults());
    expect($dotenv->isLoaded())->toBeFalse();

    $dotenv = new DotEnv([
        'dir' => 'WRONG',
    ]);
    expect($dotenv->isLoaded())->toBeFalse();

    $this->setUp();
});
