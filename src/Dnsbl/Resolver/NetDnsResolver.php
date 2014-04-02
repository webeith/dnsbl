<?php

/*
 * This file is part of the DNSBL package.
 * (c) Webeith <webeith@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dnsbl\Resolver;

use Dnsbl\Resolver\Response;

/**
 * Net DNS resolver
 *
 * @author Webeith <webeith@gmail.com>
 */
class NetDnsResolver implements InterfaceResolver
{
    public function query($hostname)
    {
        $response = new Response\NetDnsResponse();

        return $response;
    }
}
