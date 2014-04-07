<?php

namespace Tests\BL;

use Dnsbl\Dnsbl,
    Dnsbl\BL\Server;

/**
 * @group unittest
 */
class ServerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Dnsbl
     */
    private $dnsbl;

    protected function setUp()
    {
        $this->dnsbl = new Dnsbl();
    }

    /**
     * @test
     */
    public function constructor()
    {
        $resolver = $this->getMock('\Dnsbl\Resolver\InterfaceResolver');

        $bl = new Server('sp.subrl.org', $resolver, array('domain'));

        $this->assertTrue($bl->supportDomain());
        $this->assertSame('sp.subrl.org', $bl->getHostname());
        $this->assertInstanceOf('\Dnsbl\Resolver\InterfaceResolver', $bl->getResolver());
    }
}
