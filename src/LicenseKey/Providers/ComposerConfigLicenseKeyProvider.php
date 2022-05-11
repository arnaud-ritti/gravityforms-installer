<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers;

use Composer\Config;

class ComposerConfigLicenseKeyProvider implements LicenseKeyProviderInterface
{
    /**
     * The name of the config option where the key should be stored.
     */
    protected const KEY = 'gravityforms-key';
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function provide(): ?string
    {
        $value = $this->config->get(self::KEY);
        if (!is_string($value)) {
            return null;
        }
        return $value;
    }
}
