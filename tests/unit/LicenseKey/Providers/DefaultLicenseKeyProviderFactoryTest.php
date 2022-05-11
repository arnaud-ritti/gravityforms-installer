<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\Test\LicenseKey\Providers;

use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use PHPUnit\Framework\TestCase;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\ComposerConfigLicenseKeyProvider;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\CompositeLicenseKeyProvider;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\DefaultLicenseKeyProviderFactory;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\DotEnv\DotEnvLicenseKeyProvider;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\EnvironmentVariableLicenseKeyProvider;

class DefaultLicenseKeyProviderFactoryTest extends TestCase
{
    public function testBuildReturnsCompositeLicenseKeyProvider()
    {
        $composer = $this->createMock(Composer::class);
        $composer->expects($this->once())->method('getConfig')->willReturn(new Config());
        $io = $this->createMock(IOInterface::class);
        $sut = new DefaultLicenseKeyProviderFactory();
        /* @var CompositeLicenseKeyProvider $result */
        $result = $sut->build($composer, $io);
        $this->assertInstanceOf(CompositeLicenseKeyProvider::class, $result);
        $this->assertEquals(
            [
            DotEnvLicenseKeyProvider::class,
            EnvironmentVariableLicenseKeyProvider::class,
            ComposerConfigLicenseKeyProvider::class
            ],
            array_map(
                function ($provider) {
                    return get_class($provider);
                },
                $result->getProviders()
            )
        );
    }
}
