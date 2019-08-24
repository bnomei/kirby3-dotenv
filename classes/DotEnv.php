<?php

declare(strict_types=1);

namespace Bnomei;

use Dotenv\Exception\InvalidPathException;
use Kirby\Toolkit\A;
use function getenv;
use function option;

final class DotEnv
{
    /*
     * @var \Dotenv\Dotenv
     */
    private $dotenv;

    public function __construct(array $options = [])
    {
        $defaults = [
            'dir' => option('bnomei.dotenv.dir', kirby()->roots()->index()),
            'required' => option('bnomei.dotenv.required'),
        ];
        $options = array_merge($defaults, $options);

        $this->loadFromDir(A::get($options, 'dir'));
        $this->addRequired(A::get($options, 'required'));
    }

    private function loadFromDir($dir): bool
    {
        if (! $dir) {
            return false;
        }
        if (is_callable($dir)) {
            $dir = $dir();
        }
        $this->dotenv = new \Dotenv\Dotenv($dir);

        try {
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
        if (! self::$singleton) {
            self::$singleton = new self($options);
        }
        return self::$singleton->isLoaded();
    }

    public static function getenv(string $env)
    {
        self::load();
        return getenv($env);
    }
}
