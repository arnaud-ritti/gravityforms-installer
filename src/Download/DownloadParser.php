<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\Download;

use ArnaudRitti\Composer\Installers\GravityForms\Exceptions\PackageParserException;

class DownloadParser implements DownloadParserInterface
{
    /**
     * Returns if this download matches an package URL
     *
     * @param  string $url
     * @return array
     * @throws PackageParserException
     */
    public function parsePackage(string $url): array
    {
        $datas = @file_get_contents($url);
        if (empty($datas)) {
            throw new PackageParserException("No valid package datas");
        }
        return (array) unserialize($datas);
    }
}
