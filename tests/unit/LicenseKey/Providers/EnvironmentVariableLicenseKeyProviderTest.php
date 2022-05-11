<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\Test\LicenseKey\Providers;

use PHPUnit\Framework\TestCase;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\EnvironmentVariableLicenseKeyProvider;

class EnvironmentVariableLicenseKeyProviderTest extends TestCase
{
    public function testProvideWithoutEnvironmentVariableReturnsNull()
    {
        $sut = new EnvironmentVariableLicenseKeyProvider();
        $this->assertNull($sut->provide());
    }

    public function testProvideWithEnvironmentVariableReturnsValue()
    {
        $key = "8b7d3c48-caea-404b-a3b9-39883710a894";
        putenv(sprintf("%s=%s", EnvironmentVariableLicenseKeyProvider::ENV_VARIABLE_NAME, $key));
        $sut = new EnvironmentVariableLicenseKeyProvider();
        $this->assertEquals($key, $sut->provide());
    }

    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        // Clear the environment variable
        putenv(EnvironmentVariableLicenseKeyProvider::ENV_VARIABLE_NAME);
    }
}
