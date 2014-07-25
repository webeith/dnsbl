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
    public function execute($hostname)
    {
        $server = $this->getContext();
        $query  = Utils::getHostForLookup($hostname, $server->getHostname(), true);

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
            } else {
                $resultA = @$this->query($query, 'A');
                if ($resultA && isset($resultA->answer[0])) {
                    $answer = $resultA->answer[0]->address;
                }
            }

            $response->setAnswer($answer);
        }

        return $response;
    }
}
