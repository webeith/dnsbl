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
    Dnsbl\Resolver\Adapter\NetDnsAdapter,
    Dnsbl\BL\Server;

/**
 * Net DNS resolver
 *
 * @author Webeith <webeith@gmail.com>
 */
class NetDnsResolver extends NetDnsAdapter
{
    protected $supportedChecks = array(
        Server::CHECK_IPV4
    );

    public function isSupport($check)
    {
        return in_array($check, $this->supportedChecks);
    }
}
