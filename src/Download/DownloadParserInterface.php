<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\Download;

interface DownloadParserInterface
{
    /**
     * Returns if this download matches an package URL
     *
     * @param  string $url
     * @return array
     */
    public function parsePackage(string $url): array;
}
