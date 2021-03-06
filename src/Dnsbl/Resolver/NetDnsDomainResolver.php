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
    Dnsbl\BL\Server,
    Dnsbl\Utils;

/**
 * Net DNS resolver
 *
 * @author Webeith <webeith@gmail.com>
 */
class NetDnsDomainResolver extends NetDnsAdapter
{
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
        $query  = $hostname. '.' . $server->getHostname();

        $result = @$this->query($query);

        $response = new Response\NetDnsResponse();
        $response->setHostname($hostname);
        $response->setServer($server);
        $response->setQuery($query);

        if ($result) {
            $response->listed();

            $answer = '';
            $resultTXT = @$this->query($query, 'TXT');
            if ($resultTXT) {
                foreach ($resultTXT->answer as $txt) {
                    $answer .= $txt->text[0];
                }
            }

            $response->setAnswer($answer);
        }

        return $response;
    }
}
