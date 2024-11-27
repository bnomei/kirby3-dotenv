<?php

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

test('construct', function () {
    $dotenv = new Bnomei\DotEnv(defaults());
    expect($dotenv)->toBeInstanceOf(Bnomei\DotEnv::class);
});
test('load', function () {
    $dotenv = new Bnomei\DotEnv(defaults());
    expect($dotenv->isLoaded())->toBeTrue();
});
test('required', function () {
    $dotenv = new Bnomei\DotEnv(defaults() + [
        'required' => ['APP_MODE'],
    ]);
    expect($dotenv->isLoaded())->toBeTrue();

    $this->expectExceptionMessageMatches('/(One or more environment variables failed assertions: DATABASE_DSN is missing)/');
    $dotenv = new Bnomei\DotEnv(defaults() + [
        'required' => ['DATABASE_DSN'],
    ]);
});
test('static load', function () {
    expect(Bnomei\DotEnv::load(defaults()))->toBeTrue();
});
test('getenv', function () {
    Bnomei\DotEnv::load(defaults());
    $user = Bnomei\DotEnv::getenv('KIRBY_API_USER');
    expect($user)->toEqual('bnomei');
});
test('getenv default value', function () {
    Bnomei\DotEnv::load(defaults());
    $user = Bnomei\DotEnv::getenv('NOT_IN_ENV', 'bnomei');
    expect($user)->toEqual('bnomei');
});

test('static load staging', function () {
    // regular before
    expect(Bnomei\DotEnv::load(defaults()))->toBeTrue();
    $user = Bnomei\DotEnv::getenv('KIRBY_API_USER');
    expect($user)->toEqual('bnomei');

    // staging in between
    expect(Bnomei\DotEnv::load([
        'dir' => __DIR__,
        'file' => '.env.dotenv.test',
    ]))->toBeTrue();

    $user = Bnomei\DotEnv::getenv('KIRBY_API_USER');
    expect($user)->toEqual('notBnomei');

    $user = $_ENV['KIRBY_API_USER'];
    expect($user)->toEqual('notBnomei');

    // regular again
    expect(Bnomei\DotEnv::load(defaults()))->toBeTrue();
    $user = Bnomei\DotEnv::getenv('KIRBY_API_USER');
    expect($user)->toEqual('bnomei');
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
test('loaded from config based on environment/host', function () {
    $response = \Kirby\Http\Remote::get('http://dotenv.test');
    expect($response->code() === 200)->toBeTrue()
        ->and($response->content())->toEqual('staging');

    $response = \Kirby\Http\Remote::get('http://dotex.test');
    expect($response->code() === 200)->toBeTrue()
        ->and($response->content())->toEqual('production');
})->skipOnLinux();
test('load fails if missing file', function () {
    removeDotEnvFile();

    $dotenv = new Bnomei\DotEnv(defaults());
    expect($dotenv->isLoaded())->toBeFalse();

    $dotenv = new Bnomei\DotEnv([
        'dir' => 'WRONG',
    ]);
    expect($dotenv->isLoaded())->toBeFalse();

    $this->setUp();
});
