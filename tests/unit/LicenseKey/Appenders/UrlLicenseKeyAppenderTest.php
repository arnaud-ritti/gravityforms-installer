<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\Test\LicenseKey\Appenders;

use PHPUnit\Framework\TestCase;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Appenders\UrlLicenseKeyAppender;

class UrlLicenseKeyAppenderTest extends TestCase
{
    public function testAppend()
    {
        $sut = new UrlLicenseKeyAppender();
        $url = "https://example.com/download?version=1.0";
        $key = "5a66e8ff-8d6d-4d54-a15c-16746437562c";
        $this->assertEquals("{$url}&key={$key}", $sut->append($url, $key));
    }

    public function testAppendWithInvalidScheme()
    {
        $sut = new UrlLicenseKeyAppender();
        $url = "://example.com/download?version=1.0";
        $key = "5a66e8ff-8d6d-4d54-a15c-16746437562c";
        $this->expectException(\InvalidArgumentException::class);
        $sut->append($url, $key);
    }

    public function testAppendWithInvalidHost()
    {
        $sut = new UrlLicenseKeyAppender();
        $url = "https:///download?version=1.0";
        $key = "5a66e8ff-8d6d-4d54-a15c-16746437562c";
        $this->expectException(\InvalidArgumentException::class);
        $sut->append($url, $key);
    }
}
