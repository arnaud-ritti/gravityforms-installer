<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\Download;

interface DownloadMatcherInterface
{
    /**
     * Returns if this download matches an package URL
     *
     * @param  string $url
     * @return bool
     */
    public function matches(string $url): bool;
}
