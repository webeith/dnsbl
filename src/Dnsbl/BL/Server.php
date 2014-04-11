<?php

/*
 * This file is part of the DNSBL package.
 * (c) Webeith <webeith@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dnsbl\BL;

use Dnsbl\Resolver;

/**
 * Black list server
 *
 * @author Webeith <webeith@gmail.com>
 */
class Server
{
    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var Reolver\InterfaceResolver
     */
    protected $resolver;

    /**
     * @var bool
     */
    protected $supportDomain = false;

    /**
     * @var bool
     */
    protected $supportIPv4 = false;

    public function __construct($hostname, Resolver\InterfaceResolver $resolver = null, array $supports = array())
    {
        $this->setHostname($hostname);

        if (in_array('domain', $supports)) {
            $this->enableDomain(true);
        }

        if (in_array('IPv4', $supports)) {
            $this->enableIPv4(true);
        }

        if (!is_null($resolver)) {
            $this->setResolver($resolver);
        }
    }

    /**
     * Enable or disable the domain
     *
     * @param bool $value
     *
     * @return Server
     */
    public function enableDomain($value = true)
    {
        $this->supportDomain = $value;

        return $this;
    }

    /**
     * Enable or disable the IPv4
     *
     * @param bool $value
     *
     * @return Server
     */
    public function enableIPv4($value = true)
    {
        $this->supportIPv4 = $value;

        return $this;
    }

    /**
     * Server has support Domain
     *
     * @return bool
     */
    public function supportDomain()
    {
        return $this->supportDomain;
    }

    /**
     * Server has support IPv4
     *
     * @return bool
     */
    public function supportIPv4()
    {
        return $this->supportIPv4;
    }

    /**
     * Gets the value of hostname
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Sets the value of hostname
     *
     * @param string $hostnam
     *
     * @return Server
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * Gets the value of resolver
     *
     * @return Resolver\InterfaceResolver
     */
    public function getResolver()
    {
        $this->resolver->setContext($this);

        return $this->resolver;
    }

    /**
     * Sets the value of resolver
     *
     * @param Resolver\InterfaceResolver $resolver
     *
     * @return Server
     */
    public function setResolver(Resolver\InterfaceResolver $resolver)
    {
        $this->resolver = $resolver;

        return $this;
    }
}
