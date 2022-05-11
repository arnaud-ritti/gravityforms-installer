<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\Download;

class DownloadMatcher implements DownloadMatcherInterface
{
    /**
     * The url where can be downloaded (without version and key)
     */
    private const PACKAGE_URL = 'https://gravityapi.com/wp-content/plugins/gravitymanager/api.php?op=get_plugin';
    
    /**
     * Returns if this download matches an package URL
     *
     * @param  string $url
     * @return bool
     */
    public function matches(string $url): bool
    {
        return strpos($url, self::PACKAGE_URL) !== false;
    }
}
