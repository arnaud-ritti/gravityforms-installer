<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Appenders;

interface UrlLicenseKeyAppenderInterface
{
    /**
     * Appends the LicenseKey to the URL
     *
     * @param  string $url
     * @param  string $licenseKey
     * @return string
     */
    public function append(string $url, string $licenseKey): string;
}
