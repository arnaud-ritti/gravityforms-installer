<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers;

/**
 * Attempts to load the license key from an environment variable with the provided key
 */
class EnvironmentVariableLicenseKeyProvider implements LicenseKeyProviderInterface
{
    /**
     * The name of the environment variable
     * where the key should be stored.
     */
    public const ENV_VARIABLE_NAME = 'GRAVITYFORMS_KEY';

    /**
     * @inheritDoc
     */
    public function provide(): ?string
    {
        /** @var string|null $key */
        $key = getenv(self::ENV_VARIABLE_NAME);
        if (empty($key)) {
            return null;
        }
        return $key;
    }
}
