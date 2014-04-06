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
    const CHECK_IPV4 = 'ipv4';
    const CHECK_IPV6 = 'ipv6';
    const CHECK_DOMAIN = 'domain';

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var Reolver\InterfaceResolver
     */
    protected $resolver;

    /**
     * @var array
     */
    protected $supportedChecks;

    public function __construct($hostname, array $supportedChecks = array(), Resolver\InterfaceResolver $resolver = null )
    {
        $this->setSupportedChecks($supportedChecks);
        $this->setHostname($hostname);

        if (!is_null($resolver)) {
            $this->setResolver($resolver);
        }
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
            self::CHECK_IPV4,
            self::CHECK_IPV6,
            self::CHECK_DOMAIN
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
     * Enable or disable the domain
     *
     * @param bool $value
     *
     * @return Server
     */
    public function enableDomain($value = true)
    {
        if ($value) {
            $this->addCheck(self::CHECK_DOMAIN);
        } else {
            $this->removeCheck(self::CHECK_DOMAIN);
        }

        return $this;
    }

    /**
     * Enable or disable the IPv6
     *
     * @param bool $value
     *
     * @return Server
     */
    public function enableIPv6($value = true)
    {
        if ($value) {
            $this->addCheck(self::CHECK_IPV6);
        } else {
            $this->removeCheck(self::CHECK_IPV6);
        }

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
        if ($value) {
            $this->addCheck(self::CHECK_IPV4);
        } else {
            $this->removeCheck(self::CHECK_IPV4);
        }

        return $this;
    }

    /**
     * Check server has IPv4
     *
     * @return bool
     */
    public function hasIPv4()
    {
        return isset($this->supportedChecks[self::CHECK_IPV4]);
    }

    /**
     * Check server has IPv6
     *
     * @return bool
     */
    public function hasIPv6()
    {
        return isset($this->supportedChecks[self::CHECK_IPV6]);
    }

    /**
     * Check server has domain
     *
     * @return bool
     */
    public function hasDomain()
    {
        return isset($this->supportedChecks[self::CHECK_DOMAIN]);
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
        foreach ($this->supportedChecks as $check) {
            if (!$resolver->isSupport($check)) {
                throw new ServerException('Resolver ' .get_class($resolver) .' is unsupported type checking: ' . $check);
            }
        }

        $resolver->setContext($this);

        $this->resolver = $resolver;

        return $this;
    }

    /**
     * Add supported check type
     *
     * @param string $check
     *
     * @return void
     */
    protected function addCheck($check)
    {
        if (!in_array($check, $this->supportedChecks)) {
            $this->supportedChecks[] = $check;
        }
    }

    /**
     * Remove supported check type
     *
     * @param string $check
     *
     * @return void
     */
    protected function removeCheck($check)
    {
        if (in_array($check, $this->supportedChecks)) {
            $key = array_search($check, $this->supportedChecks);
            if (isset($this->supportedChecks[$key])) {
                unset($this->supportedChecks[$key]);
            }
        }
    }
}
