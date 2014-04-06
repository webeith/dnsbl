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
    public function addBlWithOutResolver()
    {
        $this->setExpectedException(
          'Dnsbl\Resolver\NotFoundResolverException', 'Set the server resolver.'
        );

        $dnsbl = new Dnsbl();
        $bl = new Server('pbl.spamhaus.org', array(Server::CHECK_IPV4, Server::CHECK_DOMAIN, Server::CHECK_IPV6));

        $this->dnsbl->addBl($bl);
    }

    /**
     * @test
     */
    public function addBl()
    {
        $resolver = $this->getMock('\Dnsbl\Resolver\InterfaceResolver');
        $resolver->expects($this->exactly(3))
            ->method('isSupport')->will(
                $this->returnValue(true)
            );

        $bl = new Server('pbl.spamhaus.org', array(Server::CHECK_IPV4, Server::CHECK_DOMAIN, Server::CHECK_IPV6));
        $bl->setResolver($resolver);

        $this->dnsbl->addBl($bl);

        $this->assertSame(
            array(
                Server::CHECK_IPV4   => array('pbl.spamhaus.org' => $bl),
                Server::CHECK_DOMAIN => array('pbl.spamhaus.org' => $bl),
                Server::CHECK_IPV6   => array('pbl.spamhaus.org' => $bl)
            ),
            $this->dnsbl->getBlackLists()
        );

        $this->assertSame(
            array('pbl.spamhaus.org' => $bl),
            $this->dnsbl->getIpv4BlackLists()
        );

        $this->assertSame(
            array('pbl.spamhaus.org' => $bl),
            $this->dnsbl->getIpv6BlackLists()
        );

        $this->assertSame(
            array('pbl.spamhaus.org' => $bl),
            $this->dnsbl->getDomainBlackLists()
        );
    }

    /**
     * @test
     */
    public function removeBl()
    {
        $resolver = $this->getMock('\Dnsbl\Resolver\InterfaceResolver');
        $resolver->expects($this->exactly(4))
            ->method('isSupport')->will(
                $this->returnValue(true)
            );

        $wsBl = new Server('pbl.spamhaus.org', array(Server::CHECK_IPV4, Server::CHECK_DOMAIN, Server::CHECK_IPV6));
        $wsBl->setResolver($resolver);
        $spBl = new Server('sp.subrl.org', array(Server::CHECK_DOMAIN));
        $spBl->setResolver($resolver);

        $this->dnsbl->addBl($wsBl);
        $this->dnsbl->addBl($spBl);

        $this->assertSame(
            array(
                Server::CHECK_IPV4   => array('pbl.spamhaus.org' => $wsBl),
                Server::CHECK_DOMAIN => array(
                    'pbl.spamhaus.org' => $wsBl,
                    'sp.subrl.org' => $spBl
                ),
                Server::CHECK_IPV6   => array('pbl.spamhaus.org' => $wsBl)
            ),
            $this->dnsbl->getBlackLists()
        );

        $this->dnsbl->removeBl('sp.subrl.org');
        $this->assertSame(
            array(
                Server::CHECK_IPV4   => array('pbl.spamhaus.org' => $wsBl),
                Server::CHECK_DOMAIN => array('pbl.spamhaus.org' => $wsBl),
                Server::CHECK_IPV6   => array('pbl.spamhaus.org' => $wsBl)
            ),
            $this->dnsbl->getBlackLists()
        );
    }

    /**
     * @test
     */
    public function checkIp()
    {
        $resolver = $this->getMock('\Dnsbl\Resolver\NetDnsIPResolver');
        $resolver->expects($this->once())
            ->method('isSupport')->will(
                $this->returnValue(true)
            );
        $resolver->expects($this->once())
            ->method('execute')->will(
                $this->returnValue(
                    $this->getMock('\Dnsbl\Resolver\Response\InterfaceResponse')
                )
            );

        $wsBl = new Server('pbl.spamhaus.org', array(Server::CHECK_IPV4));
        $wsBl->setResolver($resolver);

        $this->dnsbl->addBl($wsBl);
        $result = $this->dnsbl->checkIP('127.0.0.2');

        $this->assertTrue(is_array($result));
        $this->assertInstanceOf('\Dnsbl\Resolver\Response\InterfaceResponse', $result['pbl.spamhaus.org']);
    }

    /**
     * @test
     */
    public function checkDomain()
    {
        $resolver = $this->getMock('\Dnsbl\Resolver\NetDnsDomainResolver');
        $resolver->expects($this->once())
            ->method('isSupport')->will(
                $this->returnValue(true)
            );
        $resolver->expects($this->once())
            ->method('execute')->will(
                $this->returnValue(
                    $this->getMock('\Dnsbl\Resolver\Response\InterfaceResponse')
                )
            );

        $wsBl = new Server('dbl.spamhaus.org', array(Server::CHECK_DOMAIN));
        $wsBl->setResolver($resolver);

        $this->dnsbl->addBl($wsBl);
        $result = $this->dnsbl->checkDomain('test.com');

        $this->assertTrue(is_array($result));
        $this->assertInstanceOf('\Dnsbl\Resolver\Response\InterfaceResponse', $result['dbl.spamhaus.org']);
    }
}
