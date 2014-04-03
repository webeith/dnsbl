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
        $resolver = $this->getMock('\Dnsbl\Resolver\InterfaceResolver');
        $resolver->expects($this->once())
            ->method('isSupport')->will(
                $this->returnValue(true)
            );

        $spBl = new Server('sp.subrl.org', array(Server::CHECK_DOMAIN));
        $this->assertNull($spBl->getResolver());
        $spBl->setResolver($resolver);

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
