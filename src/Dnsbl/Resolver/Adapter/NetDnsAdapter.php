<?php

/*
 * This file is part of the DNSBL package.
 * (c) Webeith <webeith@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dnsbl\Resolver\Adapter;

@require_once('Net/DNSBL.php');

use Dnsbl\Resolver,
    Dnsbl\BL\Server;

/**
 * Dnsbl service
 *
 * @author Webeith <webeith@gmail.com>
 */
abstract class NetDnsAdapter extends \Net_DNS_Resolver implements Resolver\InterfaceResolver
{
    /**
     * @var array
     */
    protected $supportedChecks = array();

    /**
     * @var Server
     */
    protected $context;

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
     * Execute query
     *
     * @param string $hostname
     *
     * @return Resolver\Response\InterfaceResponse
     */
    abstract public function execute($hostname);

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
