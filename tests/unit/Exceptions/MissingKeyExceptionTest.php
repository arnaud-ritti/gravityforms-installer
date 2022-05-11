<?php

namespace ArnaudRitti\Composer\Installers\GravityForms\Test\Exceptions;

use PHPUnit\Framework\TestCase;
use ArnaudRitti\Composer\Installers\GravityForms\Exceptions\MissingKeyException;

class MissingKeyExceptionTest extends TestCase
{
    public function testMessage()
    {
        $message = 'testMessage';
        $e = new MissingKeyException($message);
        $this->assertEquals(
            "Could not find a license key for Gravity Forms. {$message}",
            $e->getMessage()
        );
    }
}
