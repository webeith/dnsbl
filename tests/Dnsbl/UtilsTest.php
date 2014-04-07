<?php

namespace Tests\Dnsbl;

use Dnsbl\Utils;

/**
 * @group unittest
 */
class UtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getHostForLookUp()
    {
        $this->assertSame('1.0.0.127.example.com', Utils::getHostForLookup('127.0.0.1', 'example.com', true));
        $this->assertSame('1.0.0.127.example.com', Utils::getHostForLookup('localhost', 'example.com', true));
        $this->assertSame('localhost.example.com', Utils::getHostForLookup('localhost', 'example.com', false));

    }
}
