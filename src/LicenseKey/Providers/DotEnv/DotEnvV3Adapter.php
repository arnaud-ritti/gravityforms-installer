<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\LicenseKey\Providers\DotEnv;

use Dotenv\Dotenv;

class DotEnvV3Adapter implements DotEnvAdapterInterface
{
    /**
     * @inheritDoc
     */
    public function load(string $path): void
    {
        /**
         * @noinspection PhpParamsInspection This is fallback code
         */
        $dotenv = Dotenv::create(getcwd());
        $dotenv->safeLoad();
    }
}
