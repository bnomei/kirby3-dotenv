<?php

declare(strict_types=1);

namespace Bnomei;

use Dotenv\Exception\InvalidPathException;
use Kirby\Toolkit\A;
use Kirby\Toolkit\F;

use function getenv;
use function option;

final class DotEnv
{
    /*
     * @var \Dotenv\Dotenv
     */
    private $dotenv;

    public function __construct(array $options = [], bool $useKirbyOptions = true)
    {
        $defaults = [
            'dir' => $useKirbyOptions ?
                option('bnomei.dotenv.dir') :
                realpath(__DIR__ . '/../../../../') // try plugin > site > index
            ,
            'file' =>  $useKirbyOptions ?
                option('bnomei.dotenv.file') :
                '.env'
            ,
            'required' => $useKirbyOptions ?
                option('bnomei.dotenv.required') :
                []
            ,
            'setup' => $useKirbyOptions ?
                option('bnomei.dotenv.setup') :
                function ($dotenv) {
                    return $dotenv;
                },
        ];
        $options = array_merge($defaults, $options);

        foreach (['dir', 'file'] as $key) {
            $value = A::get($options, $key);
            if ($value && is_callable($value) && ! is_string($value)) {
                $options[$key] = $value();
            }
        }

        $this->dotenv = null;
        $this->loadFromDir(A::get($options, 'dir'), A::get($options, 'file'));
        $this->addRequired(A::get($options, 'required'));
        $this->dotenv = A::get($options, 'setup')($this->dotenv);
    }

    private function loadFromDir(string $dir, string $file): bool
    {
        $dir = realpath($dir);
        if (! $dir || ! $file) {
            return false;
        }

        try {
            $this->dotenv = \Dotenv\Dotenv::createMutable($dir, $file);
            $this->dotenv->load();
        } catch (InvalidPathException $exc) {
            $this->dotenv = null;
            return false;
        }
        return true;
    }

    public function isLoaded(): bool
    {
        return ! is_null($this->dotenv);
    }

    public function addRequired(array $required = []): void
    {
        if (! $this->dotenv) {
            return;
        }
        $this->dotenv->required($required);
    }

    private static $singleton;
    public static function load(array $options = []): bool
    {
        // always load anew
        self::$singleton = new self($options, count($options) === 0);
        return self::$singleton->isLoaded();
    }

    public static function getenv(string $env, mixed $default = null)
    {
        if (! self::$singleton) {
            self::load();
        }
        return A::get($_ENV, $env, $default);
    }
}
