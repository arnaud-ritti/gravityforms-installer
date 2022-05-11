<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\Test\LicenseKey\Providers\DotEnv;

use PHPUnit\Framework\TestCase;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\DotEnv\DotEnvV5Adapter;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\EnvironmentVariableLicenseKeyProvider;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class DotEnvV5AdapterTest extends TestCase
{
    /**
     * @var callable
     */
    private $autoloader;

    public function testLoadWithKeyInEnvFileMakesItAvailable()
    {
        $key = "ab83a014-61f5-412b-9084-5c5b056105c0";
        $this->autoloader = function ($className) {
            if ($className == "Dotenv\\Dotenv") {
                $mock = new class {
                    public static function createUnsafeImmutable()
                    {
                        return new self;
                    }

                    public static function safeLoad()
                    {
                        // Load the GRAVITYFORMS_KEY with the key specified above
                        putenv(
                            sprintf(
                                "%s=%s",
                                EnvironmentVariableLicenseKeyProvider::ENV_VARIABLE_NAME,
                                "ab83a014-61f5-412b-9084-5c5b056105c0"
                            )
                        );
                    }
                };
                class_alias(get_class($mock), 'Dotenv\\Dotenv');
            }
        };
        spl_autoload_register($this->autoloader, true, true);
        $sut = new DotEnvV5Adapter();
        $this->assertFalse(getenv(EnvironmentVariableLicenseKeyProvider::ENV_VARIABLE_NAME));
        $sut->load(getcwd());
        $this->assertEquals($key, getenv(EnvironmentVariableLicenseKeyProvider::ENV_VARIABLE_NAME));
    }

    public function testLoadWithoutKeyInEnvFileDoesNotSetKey()
    {
        $this->autoloader = function ($className) {
            if ($className == "Dotenv\\Dotenv") {
                $mock = new class {
                    public static function createUnsafeImmutable()
                    {
                        return new self;
                    }

                    public static function safeLoad()
                    {
                        // Does not load anything
                        return;
                    }
                };
                class_alias(get_class($mock), 'Dotenv\\Dotenv');
            }
        };
        spl_autoload_register($this->autoloader, true, true);
        $sut = new DotEnvV5Adapter();
        $this->assertFalse(getenv(EnvironmentVariableLicenseKeyProvider::ENV_VARIABLE_NAME));
        $sut->load(getcwd());
        $this->assertFalse(getenv(EnvironmentVariableLicenseKeyProvider::ENV_VARIABLE_NAME));
    }

    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        if ($this->autoloader != null) {
            spl_autoload_unregister($this->autoloader);
        }
        putenv(EnvironmentVariableLicenseKeyProvider::ENV_VARIABLE_NAME); //Clears the environment variable
    }
}
