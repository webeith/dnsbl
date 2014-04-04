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
class NetDnsIPResolver extends NetDnsAdapter
{
    protected $supportedChecks = array(
        Server::CHECK_IPV4
    );

    public function execute($hostname)
    {
        $server = $this->getContext();
        $query  = Utils::getHostForLookup($hostname, $server->getHostname(), true);

        $result = $this->query($query);

        $response = new Resolver\Response\NetDnsResponse();
        $response->setHostname($hostname);
        $response->setServer($server);
        $response->setQuery($query);

        if ($result) {
            $response->listed();

            $resultTXT = $this->query($query, 'TXT');
            if ($resultTXT) {
                $answer = '';
                foreach ($resultTXT->answer as $txt) {
                    $answer .= $txt->text[0];
                }
            }

            $response->setAnswer($answer);
        }

        return $response;
    }
}
