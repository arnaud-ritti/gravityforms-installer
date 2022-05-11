<?php

namespace ArnaudRitti\Composer\Installers\GravityForms\Test;

use Composer\Composer;
use Composer\Config;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PreFileDownloadEvent;
use Composer\Util\RemoteFilesystem;
use PHPUnit\Framework\TestCase;
use ArnaudRitti\Composer\Installers\GravityForms\GravityFormsInstallerPlugin;
use ArnaudRitti\Composer\Installers\GravityForms\Download\DownloadMatcherInterface;
use ArnaudRitti\Composer\Installers\GravityForms\Download\DownloadParserInterface;
use ArnaudRitti\Composer\Installers\GravityForms\Download\Interceptor\BackwardsCompatibleDownloadInterceptorFactory;
use ArnaudRitti\Composer\Installers\GravityForms\Download\Interceptor\DownloadInterceptorInterface;
use ArnaudRitti\Composer\Installers\GravityForms\Exceptions\MissingKeyException;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Appenders\UrlLicenseKeyAppenderInterface;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\LicenseKeyProviderFactoryInterface;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\LicenseKeyProviderInterface;

class GravityFormsInstallerPluginTest extends TestCase
{
    public function testImplementsPluginInterface()
    {
        $this->assertInstanceOf(PluginInterface::class, new GravityFormsInstallerPlugin());
    }

    public function testImplementsEventSubscriberInterface()
    {
        $this->assertInstanceOf(EventSubscriberInterface::class, new GravityFormsInstallerPlugin());
    }

    public function testSubscribesToPreFileDownloadEvent()
    {
        $subscribedEvents = GravityFormsInstallerPlugin::getSubscribedEvents();
        $this->assertEquals(
            $subscribedEvents[PluginEvents::PRE_FILE_DOWNLOAD],
            'onPreFileDownload'
        );
    }

    public function testDeactivateDoesNothing()
    {
        $sut = new GravityFormsInstallerPlugin();
        $composer = $this->createMock(Composer::class);
        $io = $this->createMock(IOInterface::class);
        $sut->activate($composer, $io);
        $composer->expects($this->never())->method($this->anything());
        $io->expects($this->never())->method($this->anything());
        $sut->deactivate($composer, $io);
    }

    public function testUninstallDoesNothing()
    {
        $sut = new GravityFormsInstallerPlugin();
        $composer = $this->createMock(Composer::class);
        $io = $this->createMock(IOInterface::class);
        $sut->activate($composer, $io);
        $composer->expects($this->never())->method($this->anything());
        $io->expects($this->never())->method($this->anything());
        $sut->uninstall($composer, $io);
    }

    public function testOnPreFileDownloadWithNonUrlDoesNotCreateInterceptor()
    {
        $event = $this->createMock(PreFileDownloadEvent::class);
        $event->expects($this->once())->method('getProcessedUrl')->willReturn('http://example.com');

        $downloadInterceptorFactory = $this->createMock(BackwardsCompatibleDownloadInterceptorFactory::class);
        $sut = new GravityFormsInstallerPlugin(null, null, null, $downloadInterceptorFactory);
        $downloadInterceptorFactory->expects($this->never())->method('build');
        $sut->onPreFileDownload($event);
    }

    public function testOnPreFileDownloadWithoutLicenseKeyThrowsException()
    {
        $event = $this->createMock(PreFileDownloadEvent::class);
        $event->method('getProcessedUrl')->willReturn('https://example.com');

        $downloadMatcher = $this->createMock(DownloadMatcherInterface::class);
        $downloadMatcher->method('matches')->willReturn(true);

        $licenseKeyProvider = $this->createMock(LicenseKeyProviderInterface::class);
        $licenseKeyProvider->expects($this->once())->method('provide')->willReturn(null);
        $licenseKeyProviderFactory = $this->createMock(LicenseKeyProviderFactoryInterface::class);
        $licenseKeyProviderFactory->expects($this->once())->method("build")->willReturn($licenseKeyProvider);

        $composer = $this->createMock(Composer::class);
        $composer->method('getConfig')->willReturn(new Config());
        $io = $this->createMock(IOInterface::class);

        $sut = new GravityFormsInstallerPlugin($licenseKeyProviderFactory, null, $downloadMatcher);
        $sut->activate($composer, $io);
        $this->expectException(MissingKeyException::class);
        $sut->onPreFileDownload($event);
    }

    public function testOnPreFileDownloadWithUrlDoesCreateInterceptorAndInterceptsRequest()
    {
        $event = $this->createMock(PreFileDownloadEvent::class);
        $url = 'https://example.com/download?v=2.6.3';
        $key = '1234';
        $newUrl = "{$url}&key={$key}";
        $event->expects($this->once())->method('getProcessedUrl')->willReturn($url);

        $licenseKeyProvider = $this->createMock(LicenseKeyProviderInterface::class);
        $licenseKeyProvider->expects($this->once())->method('provide')->willReturn($key);

        $licenseKeyProviderFactory = $this->createMock(LicenseKeyProviderFactoryInterface::class);
        $licenseKeyProviderFactory->expects($this->once())->method('build')->willReturn($licenseKeyProvider);

        $licenseKeyAppender = $this->createMock(UrlLicenseKeyAppenderInterface::class);
        $licenseKeyAppender->expects($this->once())->method('append')->willReturn($newUrl);

        $downloadMatcher = $this->createMock(DownloadMatcherInterface::class);
        $downloadMatcher->expects($this->once())->method('matches')->with($url)->willReturn(true);

        $downloadInterceptor = $this->createMock(DownloadInterceptorInterface::class);
        $downloadInterceptor->expects($this->once())->method('intercept')->with($event, $newUrl);

        $downloadInterceptorFactory = $this->createMock(BackwardsCompatibleDownloadInterceptorFactory::class);
        $downloadInterceptorFactory->expects($this->once())->method('build')->willReturn($downloadInterceptor);

        $downloadParser = $this->createMock(DownloadParserInterface::class);
        $downloadParser
        ->expects($this->once())
        ->method('parsePackage')
        ->with($newUrl)
        ->willReturn([
            'name' => 'gravityforms',
            'title' => 'Gravity Forms',
            'version' => '2.6.3',
            'version_latest' => '2.6.3',
            'changelog' => '',
            'download_url' => 'https://example.com/download?v=2.6.3&key=1234',
            'download_url_latest' => 'https://example.com/download?v=2.6.3&key=1234',
            'purchase_url' => '',
            'thumbnail_url' => '',
            'documentation_url' => 'https://docs.gravityforms.com',
            'detail_url' => 'https://gravityforms.com',
            'price' => '',
            'message_invalid_key' => '',
            'message_valid_key' => '',
            'message_no_key' => '',
            'message_expired_key' => '',
            'addon_browser' => '',
            'is_active' => false,
            'description' => '',
            'is_featured' => '',
            'title_logo_url' => '',
            'last_published' => '2022-05-10 23:06:17',
        ]);

        $sut = new GravityFormsInstallerPlugin(
            $licenseKeyProviderFactory,
            $licenseKeyAppender,
            $downloadMatcher,
            $downloadInterceptorFactory,
            $downloadParser
        );
        $sut->activate($this->createMock(Composer::class), $this->createMock(IOInterface::class));
        $sut->onPreFileDownload($event);
    }
}
