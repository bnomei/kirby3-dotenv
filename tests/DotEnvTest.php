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
        $myself = Bnomei\DotEnv::getenv('KIRBY_API_USER');
        $this->assertMatchesRegularExpression('/(bnomei)/', $myself);
    }

    public function testStaticLoad()
    {
        $this->assertTrue(Bnomei\DotEnv::load());
    }

    public function testLoadedToPage()
    {
        $response = kirby()->render("/");
        $this->assertTrue($response->code() === 200);
        $this->assertMatchesRegularExpression('/(production)/', $response->body());
    }

    public function testLoadedFromConfig()
    {
        $this->assertEquals('bnomei', kirby()->option('no_callback'));
    }

    public function testLoadFailsIfMissingFile()
    {
        $this->removeDotEnvFile();

        $dotenv = new Bnomei\DotEnv();
        $this->assertFalse($dotenv->isLoaded());

        $dotenv = new Bnomei\DotEnv([
            'dir' => null,
        ]);
        $this->assertFalse($dotenv->isLoaded());

        $this->setUp();
    }
}
