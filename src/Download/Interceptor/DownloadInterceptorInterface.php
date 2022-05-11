<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\Download\Interceptor;

use Composer\Plugin\PreFileDownloadEvent;

/**
 * Intercepts the download and modifies it such that the license key is appended
 *
 * Interface DownloadInterceptorInterface
 * @package ArnaudRitti\Composer\Installers\GravityForms\Download
 */
interface DownloadInterceptorInterface
{
    /**
     * Intercept the download event and modify the used URL.
     *
     * @param PreFileDownloadEvent $event
     * @param string $modifiedDownloadUrl
     */
    public function intercept(PreFileDownloadEvent $event, string $modifiedDownloadUrl): void;
}
