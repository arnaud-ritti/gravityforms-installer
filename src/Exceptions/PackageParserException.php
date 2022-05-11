<?php
declare(strict_types=1);

namespace ArnaudRitti\Composer\Installers\GravityForms\Exceptions;

use \Exception;

/**
 * Exception thrown if package is not available
 */
class PackageParserException extends Exception
{
    /**
     * PackageParserException constructor.
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(
        $message = '',
        $code = 0,
        Exception $previous = null
    ) {
        parent::__construct(
            "Could not parse package. {$message}",
            $code,
            $previous
        );
    }
}
