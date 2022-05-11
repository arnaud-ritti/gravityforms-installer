<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\DotEnv;

use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\EnvironmentVariableLicenseKeyProvider;

class DotEnvLicenseKeyProvider extends EnvironmentVariableLicenseKeyProvider
{
    /**
     * @var DotEnvAdapterInterface
     */
    private $dotEnvAdapter;

    public function __construct(DotEnvAdapterInterface $dotEnvAdapter)
    {
        $this->dotEnvAdapter = $dotEnvAdapter;
    }

    /**
     * @inheritDoc
     */
    public function provide(): ?string
    {
        $currentWorkingDirectory = getcwd();
        if ($currentWorkingDirectory !== false) {
            $this->dotEnvAdapter->load($currentWorkingDirectory);
        }
        return parent::provide();
    }
}
