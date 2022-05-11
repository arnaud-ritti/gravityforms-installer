<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers;

/**
 * Interface for different kinds of loading an license Key
 *
 * Interface LicenseKeyProviderInterface
 *
 * @package ArnaudRitti\Composer\Installers\GravityForms\LicenseKeyProviders
 */
interface LicenseKeyProviderInterface
{
    /**
     * Returns the license key
     *
     * @return string|null
     */
    public function provide(): ?string;
}
