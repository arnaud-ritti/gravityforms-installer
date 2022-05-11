<?php

namespace ArnaudRitti\Composer\Installers\GravityForms\Test\Exceptions;

use PHPUnit\Framework\TestCase;
use ArnaudRitti\Composer\Installers\GravityForms\Exceptions\PackageParserException;

class PackageParserExceptionTest extends TestCase
{
    public function testMessage()
    {
        $message = 'testMessage';
        $e = new PackageParserException($message);
        $this->assertEquals(
            "Could not parse package. {$message}",
            $e->getMessage()
        );
    }
}
