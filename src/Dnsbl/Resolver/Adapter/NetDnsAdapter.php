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
     * @var Server
     */
    protected $context;

    /**
     * Execute query
     *
     * @param string $hostname
     *
     * @return Resolver\Response\InterfaceResponse
     */
    public function execute($hostname)
    {
        $server = $this->getContext();
        $query  = $this->getHostForLookup($hostname, $server->getHostname());

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

    /**
     * Get host to lookup. Lookup a host if neccessary and get the
     * complete FQDN to lookup.
     *
     * @param string $host      Host OR IP to use for building the lookup.
     * @param string $blacklist Blacklist to use for building the lookup.
     *
     * @return string Ready to use host to lookup
     */
    protected function getHostForLookup($hostname, $blacklist) 
    {
        if (filter_var($hostname, FILTER_VALIDATE_IP)) {
            $ip = $hostname;
        } else {
            $resolver = new \Net_DNS_Resolver;
            $response = $resolver->query($hostname);
            $ip       = isset($response->answer[0]->address) ? $response->answer[0]->address : null;
        }
        if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP)) {
            return;
        }

        return $this->buildLookUpHost($ip, $blacklist);
    }

    /**
     * Build the host to lookup from an IP.
     *
     * @param string $ip        IP to use for building the lookup.
     * @param string $blacklist Blacklist to use for building the lookup.
     *
     * @access protected
     * @return string Ready to use host to lookup
     */
    protected function buildLookUpHost($ip, $blacklist)
    {
        return $this->reverseIp($ip).'.'.$blacklist;
    }

    /**
     * Reverse the order of an IP. 127.0.0.1 -> 1.0.0.127. Currently
     * only works for v4-adresses
     *
     * @param string $ip IP address to reverse.
     *
     * @access protected
     * @return string Reversed IP
     */
    protected function reverseIp($ip)
    {
        return implode('.', array_reverse(explode('.', $ip)));
    }

    abstract public function isSupport($check);
}
