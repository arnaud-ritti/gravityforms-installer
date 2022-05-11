<?php

namespace ArnaudRitti\Composer\Installers\GravityForms;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PreFileDownloadEvent;
use ArnaudRitti\Composer\Installers\GravityForms\Download\DownloadMatcher;
use ArnaudRitti\Composer\Installers\GravityForms\Download\DownloadMatcherInterface;
use ArnaudRitti\Composer\Installers\GravityForms\Download\DownloadParser;
use ArnaudRitti\Composer\Installers\GravityForms\Download\DownloadParserInterface;
use ArnaudRitti\Composer\Installers\GravityForms\Download\Interceptor\BackwardsCompatibleDownloadInterceptorFactory;
use ArnaudRitti\Composer\Installers\GravityForms\Download\Interceptor\DownloadInterceptorFactoryInterface;
use ArnaudRitti\Composer\Installers\GravityForms\Exceptions\MissingKeyException;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Appenders\UrlLicenseKeyAppender;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Appenders\UrlLicenseKeyAppenderInterface;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\DefaultLicenseKeyProviderFactory;
use ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\LicenseKeyProviderFactoryInterface;

/**
 * A composer plugin that makes installing Gravity Forms
 *
 * The WordPress plugin Gravity Forms does not
 * offer a way to install it via composer natively.
 *
 * This plugin checks for downloads, and then appends the provided license key to the download URL
 *
 * With this plugin user no longer need to expose their license key in
 * composer.json.
 */
class GravityFormsInstallerPlugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @access protected
     * @var    Composer
     */
    protected $composer;

    /**
     * @access protected
     * @var    IOInterface
     */
    protected $io;

    /**
     * @var LicenseKeyProviderFactoryInterface
     */
    private $licenseKeyProviderFactory;
    /**
     * @var UrlLicenseKeyAppenderInterface
     */
    private $urlLicenseKeyAppender;
    /**
     * @var DownloadMatcherInterface
     */
    private $downloadMatcher;
    /**
     * @var DownloadInterceptorFactoryInterface
     */
    private $downloadInterceptorFactory;
    /**
     * @var DownloadParserInterface
     */
    private $downloadParser;

    /**
     * GravityFormsInstallerPlugin constructor.
     *
     * @param LicenseKeyProviderFactoryInterface|null $licenseKeyProviderFactory
     * @param UrlLicenseKeyAppenderInterface|null $urlLicenseKeyAppender
     * @param DownloadMatcherInterface|null $downloadMatcher
     * @param DownloadInterceptorFactoryInterface|null $downloadInterceptorFactory
     */
    public function __construct(
        LicenseKeyProviderFactoryInterface $licenseKeyProviderFactory = null,
        UrlLicenseKeyAppenderInterface $urlLicenseKeyAppender = null,
        DownloadMatcherInterface $downloadMatcher = null,
        DownloadInterceptorFactoryInterface $downloadInterceptorFactory = null,
        DownloadParserInterface $downloadParser = null
    ) {
        $this->licenseKeyProviderFactory = $licenseKeyProviderFactory ?? new DefaultLicenseKeyProviderFactory();
        $this->urlLicenseKeyAppender = $urlLicenseKeyAppender ?? new UrlLicenseKeyAppender();
        $this->downloadMatcher = $downloadMatcher ?? new DownloadMatcher();
        $this->downloadInterceptorFactory = $downloadInterceptorFactory ??
            new BackwardsCompatibleDownloadInterceptorFactory();
        $this->downloadParser = $downloadParser ?? new DownloadParser();
    }

    /**
     * The function that is called when the plugin is activated
     *
     * Makes composer and io available because they are needed
     * in the addKey method.
     *
     * @access public
     * @param Composer $composer The composer object
     * @param IOInterface $io Not used
     */
    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * Subscribe this Plugin to relevant Events
     *
     * Pre Download: The key needs to be added to the url
     *               (will not show up in composer.lock)
     *
     * @access public
     * @return array<string, string> An array of events that the plugin subscribes to
     * @static
     */
    public static function getSubscribedEvents()
    {
        return [
            PluginEvents::PRE_FILE_DOWNLOAD => 'onPreFileDownload'
        ];
    }

    /**
     * Checks if the download is an Gravity Froms package, if so it appends the license key to the URL
     *
     * The key is not added to the package because it would show up in the
     * composer.lock file in this case.
     * The interceptor swaps out the Gravity Froms url with a url that contains the key.
     *
     * @access public
     * @param PreFileDownloadEvent $event The event that called this method
     * @throws MissingKeyException
     */
    public function onPreFileDownload(PreFileDownloadEvent $event): void
    {
        $packageUrl = $event->getProcessedUrl();

        if (!$this->downloadMatcher->matches($packageUrl)) {
            return;
        }
        $downloadInterceptor = $this->downloadInterceptorFactory->build(
            PluginInterface::PLUGIN_API_VERSION,
            $this->composer,
            $this->io
        );
        $packageDatas = $this->downloadParser->parsePackage($this->urlLicenseKeyAppender->append($packageUrl, $this->getLicenseKey()));
        if (empty($packageDatas["download_url_latest"])) {
            return;
        }
        $downloadInterceptor->intercept(
            $event,
            $packageDatas["download_url_latest"]
        );
    }

    /**
     * Get the license key
     *
     * @access protected
     * @return string The key from the environment
     * @throws MissingKeyException
     */
    private function getLicenseKey()
    {
        $licenseKeyProvider = $this->licenseKeyProviderFactory->build($this->composer, $this->io);
        $key = $licenseKeyProvider->provide();
        if ($key === null) {
            throw new MissingKeyException("No valid license key could be found");
        }
        return $key;
    }

    /**
     * @inheritDoc
     */
    public function deactivate(Composer $composer, IOInterface $io): void
    {
        // https://github.com/composer/composer/blob/master/UPGRADE-2.0.md#for-integrators-and-plugin-authors
        // Plugins implementing EventSubscriberInterface
        // will be deregistered from the EventDispatcher automatically when being deactivated, nothing to do there.
    }

    /**
     * @inheritDoc
     */
    public function uninstall(Composer $composer, IOInterface $io): void
    {
        // Nothing to uninstall
    }
}
