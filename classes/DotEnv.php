<?php

declare(strict_types=1);

namespace Bnomei;

use Closure;
use Dotenv\Exception\InvalidPathException;
use Kirby\Toolkit\A;

use function option;

final class DotEnv
{
    private ?\Dotenv\Dotenv $dotenv = null;

    private array $options;

    public function __construct(array $options = [], bool $canUseKirbyOptions = true)
    {
        $defaults = $canUseKirbyOptions ? [
            'debug' => option('debug'),
            'dir' => option('bnomei.dotenv.dir'),
            'file' => option('bnomei.dotenv.file'),
            'environment' => option('bnomei.dotenv.environment'),
            'required' => option('bnomei.dotenv.required'),
            'setup' => option('bnomei.dotenv.setup'),
        ] : [
            'debug' => false,
            'dir' => [],
            'file' => '.env',
            'environment' => null,
            'required' => [],
            'setup' => function ($dotenv) {
                return $dotenv;
            },
        ];
        $this->options = array_merge($defaults, $options);

        // allow callback for a few options
        foreach ($this->options as $key => $value) {
            if ($value instanceof Closure && in_array($key, ['dir', 'file', 'environment', 'required'])) {
                $this->options[$key] = $value();
            }
        }

        // try multiple reasonable dirs
        $dirs = $this->options['dir'];
        if (! is_array($dirs)) {
            $dirs = [$dirs];
        }
        foreach ($dirs as $dir) {
            if (empty($dir)) {
                continue;
            }
            // more specific file first as it will break on first success
            $files = [
                $this->options['file'].'.'.$this->options['environment'],
                $this->options['file'],
            ];
            foreach ($files as $file) {
                if ($this->loadFromDir($dir, $file)) {
                    break;
                }
            }
        }

        // add rules for required env vars
        $this->addRequired($this->options['required']);

        // allow additional last step setup
        $this->dotenv = $this->options['setup']($this->dotenv);
    }

    public function option(string $key): mixed
    {
        return A::get($this->options, $key);
    }

    private function loadFromDir(string $dir, string $file): bool
    {
        // fail silently as we are trying multiple dirs that might not exist
        $dir = realpath($dir);
        if (! $dir || ! file_exists($dir.'/'.$file)) {
            return false;
        }

        try {
            $this->dotenv = \Dotenv\Dotenv::createMutable($dir, $file);
            $this->dotenv->load();
        } catch (InvalidPathException $exc) {
            // file exists but is not readable
            if ($this->option('debug')) {
                throw $exc;
            }
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

    private static ?self $singleton = null;

    public static function load(array $options = [], bool $canUseKirbyOptions = true): bool
    {
        // always load anew
        self::$singleton = new self($options, $canUseKirbyOptions);

        return self::$singleton->isLoaded();
    }

    public static function getenv(string $env, mixed $default = null): mixed
    {
        if (! self::$singleton) {
            self::load();
        }

        return A::get($_ENV, $env, $default);
    }
}
