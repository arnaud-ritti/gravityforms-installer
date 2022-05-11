<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers;

use Composer\Composer;
use Composer\IO\IOInterface;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\DotEnv\DotEnvAdapterFactory;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\DotEnv\DotEnvLicenseKeyProvider;

class DefaultLicenseKeyProviderFactory implements LicenseKeyProviderFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function build(Composer $composer, IOInterface $io): LicenseKeyProviderInterface
    {
        return new CompositeLicenseKeyProvider(
            new DotEnvLicenseKeyProvider(DotEnvAdapterFactory::build()),
            new EnvironmentVariableLicenseKeyProvider(),
            new ComposerConfigLicenseKeyProvider($composer->getConfig())
        );
    }
}
