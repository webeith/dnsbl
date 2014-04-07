<?php

/*
 * This file is part of the DNSBL package.
 * (c) Webeith <webeith@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dnsbl\Resolver;

use Dnsbl\Resolver\Response,
    Dnsbl\Resolver\InterfaceResolver,
    Dnsbl\BL\Server;

/**
 * Url resolver
 *
 * @author Webeith <webeith@gmail.com>
 */
class UrlResolver implements InterfaceResolver
{
    /**
     * @var strging
     */
    protected $location;

    /**
     * @var Server
     */
    protected $context;

    /**
     * @var array
     */
    protected $supportedChecks = array();

    /**
     * Constructro
     *
     * @param string $location
     * @param array  $supportedChecks
     *
     * @return void
     */
    public function __construct($location = null, array $supportedChecks = array())
    {
        $this->setSupportedChecks($supportedChecks);
        $this->setLocation($location);
    }

    /**
     * Execute query
     *
     * @param string $hostname
     *
     * @return Dnsbl\Resolver\Response\InterfaceResponse
     */
    public function execute($hostname)
    {
        $server = $this->getContext();

        $result = @file($this->location);

        $response = new Response\NetDnsResponse();
        $response->setHostname($hostname);
        $response->setServer($server);
        $response->setQuery($this->location);

        if ($result) {
            foreach ($result as $value) {
                if (trim($hostname) === trim($value)) {
                    $response->listed();
                }
            }
        }

        return $response;
    }

    /**
     * Sets the value of location
     *
     * @param string $location
     *
     * @return FileResolver
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Resolver is supported check
     *
     * @param string $check
     *
     * @return bool
     */
    public function isSupport($check)
    {
        return in_array($check, $this->supportedChecks);
    }

    /**
     * Gets the value of supportedChecks
     *
     * @return string
     */
    public function getSupportedChecks()
    {
        return $this->supportedChecks;
    }

    /**
     * Sets the value of supportedChecks
     *
     * @param string $supportedChecks
     *
     * @return Server
     */
    public function setSupportedChecks(array $supportedChecks)
    {
        $checks = array(
            Server::CHECK_IPV4,
            Server::CHECK_IPV6,
            Server::CHECK_DOMAIN
        );

        foreach ($supportedChecks as $check) {
            if (!in_array($check, $checks)) {
                throw new ServerException($check . ' is unsupported type checking');
            }
        }
        $this->supportedChecks = $supportedChecks;

        return $this;
    }

    /**
     * Sets the context of resolver
     *
     * @param Dnsbl\BL\Server $context
     *
     * @return Resolver\InterfaceResolver
     */
    public function setContext(Server $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Gets the value of resolver context
     *
     * @return \Dnsbl\BL\Server
     */
    public function getContext()
    {
        return $this->context;
    }
}
