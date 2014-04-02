<?php

namespace Tests\Dnsbl;

use Dnsbl\Dnsbl,
    Dnsbl\BL\Server;

/**
 * @group unittest
 */
class DnsblTest extends \PHPUnit_Framework_TestCase
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
    public function shouldSetDefaultResolverAfterCreate()
    {
        $dnsbl = new Dnsbl();

        $this->assertInstanceOf('\Dnsbl\Resolver\InterfaceResolver', $dnsbl->getDefaultResolver());
    }

    /**
     * @test
     */
    public function addBl()
    {
        $bl = new Server('ws.subrl.org', array(Server::CHECK_IPV4, Server::CHECK_DOMAIN, Server::CHECK_IPV6));

        $this->dnsbl->addBl($bl);

        $this->assertSame(
            array(
                Server::CHECK_IPV4   => array('ws.subrl.org' => $bl),
                Server::CHECK_DOMAIN => array('ws.subrl.org' => $bl),
                Server::CHECK_IPV6   => array('ws.subrl.org' => $bl)
            ),
            $this->dnsbl->getBlackLists()
        );

        $this->assertSame(
            array('ws.subrl.org' => $bl),
            $this->dnsbl->getIpv4BlackLists()
        );

        $this->assertSame(
            array('ws.subrl.org' => $bl),
            $this->dnsbl->getIpv6BlackLists()
        );

        $this->assertSame(
            array('ws.subrl.org' => $bl),
            $this->dnsbl->getDomainBlackLists()
        );

    }

    /**
     * @test
     */
    public function removeBl()
    {
        $wsBl = new Server('ws.subrl.org', array(Server::CHECK_IPV4, Server::CHECK_DOMAIN, Server::CHECK_IPV6));
        $spBl = new Server('sp.subrl.org', array(Server::CHECK_DOMAIN));

        $this->dnsbl->addBl($wsBl);
        $this->dnsbl->addBl($spBl);

        $this->assertSame(
            array(
                Server::CHECK_IPV4   => array('ws.subrl.org' => $wsBl),
                Server::CHECK_DOMAIN => array(
                    'ws.subrl.org' => $wsBl,
                    'sp.subrl.org' => $spBl
                ),
                Server::CHECK_IPV6   => array('ws.subrl.org' => $wsBl)
            ),
            $this->dnsbl->getBlackLists()
        );

        $this->dnsbl->removeBl('sp.subrl.org');
        $this->assertSame(
            array(
                Server::CHECK_IPV4   => array('ws.subrl.org' => $wsBl),
                Server::CHECK_DOMAIN => array('ws.subrl.org' => $wsBl),
                Server::CHECK_IPV6   => array('ws.subrl.org' => $wsBl)
            ),
            $this->dnsbl->getBlackLists()
        );
    }

    /**
     * @test
     */
    public function check()
    {
        $wsBl = new Server('ws.subrl.org', array(Server::CHECK_DOMAIN));

        $this->dnsbl->addBl($wsBl);
        $result = $this->dnsbl->check('example.com');

        $this->assertTrue(is_array($result));
        $this->assertInstanceOf('\Dnsbl\Resolver\Response\InterfaceResponse', $result['ws.subrl.org']);
    }
}
