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
    public function addBlServerWithOutResolver()
    {
        $this->setExpectedException(
          'Dnsbl\Resolver\NotFoundResolverException', 'Set the server resolver.'
        );

        $dnsbl = new Dnsbl();
        $bl = new Server('pbl.spamhaus.org');

        $this->dnsbl->addBlServer($bl);
    }

    /**
     * @test
     */
    public function addBlServer()
    {
        $resolver = $this->getMock('\Dnsbl\Resolver\InterfaceResolver');

        $bl = new Server('pbl.spamhaus.org', $resolver, array('domain'));
        $this->dnsbl->addBlServer($bl);

        $this->assertSame(
            array($bl),
            $this->dnsbl->getBlServers()
        );
    }

    /**
     * @test
     */
    public function setBlServers()
    {
        $resolver = $this->getMock('\Dnsbl\Resolver\InterfaceResolver');

        $wsBl = new Server('pbl.spamhaus.org', $resolver, array('domain'));
        $spBl = new Server('sp.subrl.org', $resolver);
        $this->dnsbl->setBlServers(
            array($wsBl, $spBl)
        );

        $this->assertSame(
            array($wsBl, $spBl),
            $this->dnsbl->getBlServers()
        );
    }

    /**
     * @test
     */
    public function check()
    {
        $resolver = $this->getMock('\Dnsbl\Resolver\NetDnsIPResolver');
        $resolver->expects($this->once())
            ->method('execute')->will(
                $this->returnValue(
                    $this->getMock('\Dnsbl\Resolver\Response\InterfaceResponse')
                )
            );

        $wsBl = $this->getMockBuilder('\Dnsbl\BL\Server')
            ->disableOriginalConstructor()
            ->getMock();
        $wsBl->expects($this->exactly(2))
            ->method('getResolver')->will(
                $this->returnValue($resolver)
            );
        $wsBl->expects($this->never())
            ->method('supportDomain')->will(
                $this->returnValue(
                    $this->getMock('\Dnsbl\Resolver\Response\InterfaceResponse')
                )
            );
        $wsBl->expects($this->never())
            ->method('supportIPv4')->will(
                $this->returnValue(
                    $this->getMock('\Dnsbl\Resolver\Response\InterfaceResponse')
                )
            );
        $wsBl->setResolver($resolver);

        $this->dnsbl->addBlServer($wsBl);
        $result = $this->dnsbl->check('example.com');

        $this->assertTrue(is_array($result));
        $this->assertInstanceOf('\Dnsbl\Resolver\Response\InterfaceResponse', $result[0]);
    }

    /**
     * @test
     */
    public function checkIP()
    {
        $resolver = $this->getMock('\Dnsbl\Resolver\NetDnsIPResolver');
        $resolver->expects($this->once())
            ->method('execute')->will(
                $this->returnValue(
                    $this->getMock('\Dnsbl\Resolver\Response\InterfaceResponse')
                )
            );

        $wsBl = $this->getMockBuilder('\Dnsbl\BL\Server')
            ->disableOriginalConstructor()
            ->getMock();
        $wsBl->expects($this->exactly(2))
            ->method('getResolver')->will(
                $this->returnValue($resolver)
            );
        $wsBl->expects($this->never())
            ->method('supportDomain')->will(
                $this->returnValue(
                    $this->getMock('\Dnsbl\Resolver\Response\InterfaceResponse')
                )
            );
        $wsBl->expects($this->once())
            ->method('supportIPv4')->will(
                $this->returnValue(
                    $this->getMock('\Dnsbl\Resolver\Response\InterfaceResponse')
                )
            );
        $wsBl->setResolver($resolver);

        $this->dnsbl->addBlServer($wsBl);
        $result = $this->dnsbl->checkIP('127.0.0.2');

        $this->assertTrue(is_array($result));
        $this->assertInstanceOf('\Dnsbl\Resolver\Response\InterfaceResponse', $result[0]);
    }

    /**
     * @test
     */
    public function checkDomain()
    {
        $resolver = $this->getMock('\Dnsbl\Resolver\NetDnsIPResolver');
        $resolver->expects($this->once())
            ->method('execute')->will(
                $this->returnValue(
                    $this->getMock('\Dnsbl\Resolver\Response\InterfaceResponse')
                )
            );

        $wsBl = $this->getMockBuilder('\Dnsbl\BL\Server')
            ->disableOriginalConstructor()
            ->getMock();
        $wsBl->expects($this->exactly(2))
            ->method('getResolver')->will(
                $this->returnValue($resolver)
            );
        $wsBl->expects($this->once())
            ->method('supportDomain')->will(
                $this->returnValue(
                    $this->getMock('\Dnsbl\Resolver\Response\InterfaceResponse')
                )
            );
        $wsBl->expects($this->never())
            ->method('supportIPv4')->will(
                $this->returnValue(
                    $this->getMock('\Dnsbl\Resolver\Response\InterfaceResponse')
                )
            );
        $wsBl->setResolver($resolver);

        $this->dnsbl->addBlServer($wsBl);
        $result = $this->dnsbl->checkDomain('example.com');

        $this->assertTrue(is_array($result));
        $this->assertInstanceOf('\Dnsbl\Resolver\Response\InterfaceResponse', $result[0]);
    }
}
