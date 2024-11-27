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
        $defaults = [
            'debug' => $canUseKirbyOptions ? option('debug') : false,
            'dir' => $canUseKirbyOptions ? option('bnomei.dotenv.dir') : [],
            'file' => $canUseKirbyOptions ? option('bnomei.dotenv.file') : '.env',
            'environment' => $canUseKirbyOptions ? option('bnomei.dotenv.environment') : null,
            'required' => $canUseKirbyOptions ? option('bnomei.dotenv.required') : [],
            'setup' => $canUseKirbyOptions ? option('bnomei.dotenv.setup') : function ($dotenv) {
                return $dotenv;
            },
        ];
        $this->options = array_merge($defaults, $options);

        $this->allowCallbackForAFewOptions();
        $this->tryMultipleReasonableDirs();
        $this->addRequired($this->options['required']);
        // allow additional last step setup
        $this->dotenv = $this->options['setup']($this->dotenv);
    }

    public function option(string $key): mixed
    {
        return A::get($this->options, $key);
    }

    private function tryMultipleReasonableDirs(): void
    {
        // try multiple reasonable dirs
        $dirs = $this->options['dir'];
        if (! is_array($dirs)) {
            $dirs = [$dirs];
        }
        $dirs = array_filter($dirs, function ($dir) {
            return ! empty($dir);
        });
        $files = [
            $this->options['file'].'.'.$this->options['environment'],
            $this->options['file'],
        ];
        foreach ($dirs as $dir) {
            foreach ($files as $file) {
                if ($this->loadFromDir($dir, $file)) {
                    break 2;
                }
            }
        }
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

    public function allowCallbackForAFewOptions(): void
    {
        // allow callback for a few options
        foreach ($this->options as $key => $value) {
            if ($value instanceof Closure && in_array($key, ['dir', 'file', 'environment', 'required'])) {
                $this->options[$key] = $value();
            }
        }
    }
}
