<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Bnomei\DotEnv;
use PHPUnit\Framework\TestCase;

class DotEnvTest extends TestCase
{
    public function setUp(): void
    {
        if (!file_exists(__DIR__ . '/.env')) {
            copy(
                __DIR__ . '/.env.example',
                __DIR__ . '/.env'
            );
        }
    }

    public function removeDotEnvFile(): void
    {
        $dotenv = __DIR__ . '/.env';
        if (file_exists($dotenv)) {
            unlink($dotenv);
        }
    }

    public function testConstruct()
    {
        $dotenv = new Bnomei\DotEnv();
        $this->assertInstanceOf(Bnomei\DotEnv::class, $dotenv);
    }

    public function testLoad()
    {
        $dotenv = new Bnomei\DotEnv();
        $this->assertTrue($dotenv->isLoaded());
    }

    public function testRequired()
    {
        $dotenv = new Bnomei\DotEnv([
            'required' => ['APP_MODE']
        ]);
        $this->assertTrue($dotenv->isLoaded());

        $this->expectExceptionMessageMatches('/(One or more environment variables failed assertions: DATABASE_DSN is missing)/');
        $dotenv = new Bnomei\DotEnv([
            'required' => ['DATABASE_DSN']
        ]);
    }

    public function testGetenv()
    {
        $user = Bnomei\DotEnv::getenv('KIRBY_API_USER');
        $this->assertEquals('bnomei', $user);
    }

    public function testGetenvDefaultValue()
    {
        $user = Bnomei\DotEnv::getenv('NOT_IN_ENV', 'bnomei');
        $this->assertEquals('bnomei', $user);
    }

    public function testStaticLoad()
    {
        $this->assertTrue(Bnomei\DotEnv::load());
    }

    public function testStaticLoadStaging()
    {
        // regular before
        $this->assertTrue(Bnomei\DotEnv::load());
        $user = Bnomei\DotEnv::getenv('KIRBY_API_USER');
        $this->assertEquals('bnomei', $user);

        // staging inbetween
        $this->assertTrue(Bnomei\DotEnv::load([
            'dir' => __DIR__,
            'file' => '.env.staging',
        ]));

        $user = Bnomei\DotEnv::getenv('KIRBY_API_USER');
        $this->assertEquals('notBnomei', $user);

        $user = $_ENV['KIRBY_API_USER'];
        $this->assertEquals('notBnomei', $user);

        // regular again
        $this->assertTrue(Bnomei\DotEnv::load());
        $user = Bnomei\DotEnv::getenv('KIRBY_API_USER');
        $this->assertEquals('bnomei', $user);
    }

    public function testLoadedToPage()
    {
        $response = kirby()->render("/");
        $this->assertTrue($response->code() === 200);
        $this->assertMatchesRegularExpression('/(production)/', $response->body());
    }

    public function testLoadedFromConfig()
    {
        $callback = kirby()->option('var_from_env'); // => a closure
        $this->assertEquals('production', $callback());
    }

    public function testLoadFailsIfMissingFile()
    {
        $this->removeDotEnvFile();

        $dotenv = new Bnomei\DotEnv();
        $this->assertFalse($dotenv->isLoaded());

        $dotenv = new Bnomei\DotEnv([
            'dir' => 'WRONG',
        ]);
        $this->assertFalse($dotenv->isLoaded());

        $this->setUp();
    }
}
