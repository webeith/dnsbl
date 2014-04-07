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
 * Abstract resolver
 *
 * @author Webeith <webeith@gmail.com>
 */
abstract class UrlResolver implements InterfaceResolver
{
    /**
     * @var Server
     */
    protected $context;

    /**
     * Execute query
     *
     * @param string $hostname
     *
     * @return Dnsbl\Resolver\Response\InterfaceResponse
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
