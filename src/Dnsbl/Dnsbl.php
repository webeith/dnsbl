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
    protected $blServerss = array();

    /**
     * Check hostname in all bl servers
     *
     * @param string $hostname
     *
     * @return Dnsbl\Resolver\Response\InterfaceResponse
     */
    public function check($hostname)
    {
        $result = array();
        foreach ($this->getBlServers() as $blServer) {
            $result[] = $blServer->getResolver()->execute($hostname);
        }

        return $result;
    }

    /**
     * Check IP in black list
     *
     * @param string $ip
     *
     * @return Dnsbl\Resolver\Response\InterfaceResponse
     */
    public function checkIP($ip)
    {
        $result = array();
        foreach ($this->getBlServers() as $blServer) {
            if ($blServer->supportIPv4()) {
                $result[] = $blServer->getResolver()->execute($ip);
            }
        }

        return $result;
    }
    /**
     * Check domain name in black list
     *
     * @param string $domain
     *
     * @return Dnsbl\Resolver\Response\InterfaceResponse
     */
    public function checkDomain($hostname)
    {
        $result = array();
        foreach ($this->getBlServers() as $blServer) {
            if ($blServer->supportDomain()) {
                $result[] = $blServer->getResolver()->execute($hostname);
            }
        }

        return $result;
    }

    /**
     * Add the server to BlServers
     *
     * @param Server $server
     *
     * @exception Resolver\NotFoundResolverException
     *
     * @return Dnsbl
     */
    public function addBlServer(Server $server)
    {
        if (is_null($server->getResolver())) {
            throw new Resolver\NotFoundResolverException('Set the server resolver.');
        }

        $this->blServers[] = $server;

        return $this;
    }

    /**
     * Add the server to BlServers
     *
     * @param Server $server
     *
     * @exception Resolver\NotFoundResolverException
     *
     * @return Dnsbl
     */
    public function setBlServers(array $servers)
    {
        $this->blServers = array();
        foreach ($servers as $server) {
            if ($server instanceof Server) {
                if (is_null($server->getResolver())) {
                    throw new Resolver\NotFoundResolverException('Set the server resolver.');
                }

                $this->blServers[] = $server;
            }
        }

        return $this;
    }

    /**
     * Gets the value of BlServers
     *
     * @return array
     */
    public function getBlServers()
    {
        return $this->blServers;
    }
}
