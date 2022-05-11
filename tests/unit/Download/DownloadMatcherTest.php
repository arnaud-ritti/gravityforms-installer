<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\Test\Download;

use PHPUnit\Framework\TestCase;
use ArnaudRitti\Composer\Installers\GravityForms\Download\DownloadMatcher;

class DownloadMatcherTest extends TestCase
{
    public function testMatchesWithUrlReturnsTrue()
    {
        // phpcs:ignore
        $url = "https://gravityapi.com/wp-content/plugins/gravitymanager/api.php?op=get_plugin&slug=gravityforms&key=938C927AFC694954A84476CF3CBD28B3";
        $sut = new DownloadMatcher();
        $this->assertTrue($sut->matches($url));
    }

    public function testMatchesWithOtherUrlReturnsFalse()
    {
        $url = "https://example.com/download?key=938C927AFC694954A84476CF3CBD28B3";
        $sut = new DownloadMatcher();
        $this->assertFalse($sut->matches($url));
    }
}
