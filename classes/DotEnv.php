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

    public function __construct(array $options = [], bool $fromConfig = true)
    {
        $defaults = [
            'dir' => $fromConfig ?
                option('bnomei.dotenv.dir', kirby()->roots()->index()) :
                realpath(__DIR__ . '/../../../../') // try plugin > site > index
            ,
            'file' =>  $fromConfig ?
                option('bnomei.dotenv.file', '.env') :
                '.env'
            ,
            'required' => $fromConfig ?
                option('bnomei.dotenv.required', []) :
                []
            ,
        ];
        $options = array_merge($defaults, $options);

        $this->loadFromDir(A::get($options, 'dir'), A::get($options, 'file'));
        $this->addRequired(A::get($options, 'required'));
    }

    private function loadFromDir($dir, $file = '.env'): bool
    {
        if (! $dir) {
            return false;
        }
        if (is_callable($dir)) {
            $dir = $dir();
        }
        if (! $file) {
            return false;
        }
        if (is_callable($file)) {
            $file = $file();
        }
        $this->dotenv = new \Dotenv\Dotenv($dir, $file);

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
    public static function load(array $options = [], bool $fromConfig = true): bool
    {
        if (! self::$singleton) {
            self::$singleton = new self($options, $fromConfig);
        }
        return self::$singleton->isLoaded();
    }

    public static function getenv(string $env, array $options = [])
    {
        self::load($options, count($options) === 0);
        return getenv($env);
    }
}
