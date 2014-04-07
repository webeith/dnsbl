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
    Dnsbl\Resolver\AbstractResolver,
    Dnsbl\BL\Server;

/**
 * Url resolver
 *
 * @author Webeith <webeith@gmail.com>
 */
class UrlResolver implements AbstractResolver
{
    /**
     * @var strging
     */
    protected $location;

    /**
     * Constructor
     *
     * @param string $location
     * @param array  $supportedChecks
     *
     * @return void
     */
    public function __construct($location = null)
    {
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
}
