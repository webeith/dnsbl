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
    public function shouldSetDefaultResolverAfterSetInBl()
    {
        $spBl = new Server('sp.subrl.org', array(Server::CHECK_DOMAIN));
        $this->assertNull($spBl->getResolver());

        $this->dnsbl->addBl($spBl);
        $this->assertInstanceOf('\Dnsbl\Resolver\InterfaceResolver', $spBl->getResolver());
    }

    /**
     * @test
     */
    public function shouldCheckSupportedTypes()
    {
        $this->setExpectedException(
          '\Dnsbl\BL\ServerException', 'Foo is unsupported type checking'
        );

        $spBl = new Server('sp.subrl.org', array(Server::CHECK_DOMAIN, 'Foo'));
    }
}
