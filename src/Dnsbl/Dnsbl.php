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
    /**
     * @var array
     */
    protected $blackLists = array();

    public function checkDomain($domain)
    {
        $result = array();
        foreach ($this->getDomainBlackLists() as $server) {
            $result[$server->getHostname()] = $server->getResolver()->execute($domain);
        }

        return $result;
    }

    public function checkIP($ip)
    {
        $result = array();
        foreach ($this->getIpv4BlackLists() as $server) {
            $result[$server->getHostname()] = $server->getResolver()->execute($ip);
        }

        return $result;
    }

    public function checkIPv6($ip)
    {
        $result = array();
        foreach ($this->getIpv6BlackLists() as $server) {
            $result[$server->getHostname()] = $server->getResolver()->execute($ip);
        }

        return $result;
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
            throw new Resolver\NotFoundResolverException('Set the server resolver.');
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
