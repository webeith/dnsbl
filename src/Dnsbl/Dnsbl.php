<?php

/*
 * This file is part of the DNSBL package.
 * (c) Webeith <webeith@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dnsbl;

use Dnsbl\Resolver,
    Dnsbl\BL\Server;

/**
 * Dnsbl service
 *
 * @author Webeith <webeith@gmail.com>
 */
class Dnsbl
{
    protected $blackLists = array();

    /**
     * @var Resolver\InterfaceResolver
     */
    protected $defaultResolver;

    public function __construct()
    {
        $this->defaultResolver = new Resolver\NetDnsResolver();
    }

    public function check($hostname)
    {
        $result = array();
        foreach ($this->getDomainBlackLists() as $server) {
            $result[$server->getHostname()] = $server->getResolver()->query($hostname);
        }

        return $result;
    }

    /**
     * Get the default resolver
     *
     * @return Resolver\InterfaceResolver
     */
    public function getDefaultResolver()
    {
        return $this->defaultResolver;
    }

    /**
     * Sets the default resolver
     *
     * @param Resolver\InterfaceResolver $resolver
     *
     * @return Dnsbl
     */
    public function setDefaultResolver(Resolver\InterfaceResolver $resolver)
    {
        $this->defaultResolver = $resolver;

        return $this;
    }

    /**
     * Add the value to BlackLists and set default resolver
     *
     * @param Server $server
     *
     * @return Dnsbl
     */
    public function addBl(Server $server)
    {
        if (is_null($server->getResolver())) {
            $server->setResolver($this->getDefaultResolver());
        }

        foreach($server->getSupportedChecks() as $check) {
            $this->blackList[$check][$server->getHostname()] = $server;
        }

        return $this;
    }

    /**
     * Gets the value of BlackLists
     *
     * @return array
     */
    public function getBlackLists()
    {
        return $this->blackList;
    }

    /**
     * Gets the ipv4 of BlackLists
     *
     * @return array
     */
    public function getIpv4BlackLists()
    {
        return $this->blackList[Server::CHECK_IPV4];
    }

    /**
     * Gets the ipv6 of BlackLists
     *
     * @return array
     */
    public function getIpv6BlackLists()
    {
        return $this->blackList[Server::CHECK_IPV6];
    }

    /**
     * Gets the domain of BlackLists
     *
     * @return array
     */
    public function getDomainBlackLists()
    {
        return $this->blackList[Server::CHECK_DOMAIN];
    }

    /**
     * Remove the value from BlackLists
     *
     * @param string $blackList
     *
     * @return Dnsbl
     */
    public function removeBl($blackList)
    {
        if (isset($this->blackList[Server::CHECK_IPV4][$blackList])) {
            unset($this->blackList[Server::CHECK_IPV4][$blackList]);
        }

        if (isset($this->blackList[Server::CHECK_IPV6][$blackList])) {
            unset($this->blackList[Server::CHECK_IPV6][$blackList]);
        }

        if (isset($this->blackList[Server::CHECK_DOMAIN][$blackList])) {
            unset($this->blackList[Server::CHECK_DOMAIN][$blackList]);
        }

        return $this;
    }
}
