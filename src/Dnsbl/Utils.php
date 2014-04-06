<?php

/*
 * This file is part of the DNSBL package.
 * (c) Webeith <webeith@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dnsbl;

/**
 * Dnsbl service
 *
 * @author Webeith <webeith@gmail.com>
 */
class Utils
{
    /**
     * Get host to lookup. Lookup a host if neccessary and get the
     * complete FQDN to lookup.
     *
     * @param string $host      Host OR IP to use for building the lookup.
     * @param string $blacklist Blacklist to use for building the lookup.
     * @param string $isIP      is IP adress?
     *
     * @return string Ready to use host to lookup
     */
    public static function getHostForLookup($hostname, $blacklist, $isIP = true)
    {
        if ($isIP && !filter_var($hostname, FILTER_VALIDATE_IP)) {
            $resolver = new \Net_DNS_Resolver;
            $response = $resolver->query($hostname);
            $ip = isset($response->answer[0]->address) ? $response->answer[0]->address : null;

            if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP)) {
                return null;
            }

            return self::buildLookUpIP($ip, $blacklist);
        }

        return self::buildLookUpHost($hostname, $blacklist);
    }

    /**
     * Build the host to lookup from an IP.
     *
     * @param string $ip        IP to use for building the lookup.
     * @param string $blacklist Blacklist to use for building the lookup.
     *
     * @return string Ready to use host to lookup
     */
    protected static function buildLookUpIP($ip, $blacklist)
    {
        return self::reverseIp($ip).'.'.$blacklist;
    }

    /**
     * Build the host to lookup from an hostname.
     *
     * @param string $hostname  Hostname to use for building the lookup.
     * @param string $blacklist Blacklist to use for building the lookup.
     *
     * @return string Ready to use host to lookup
     */
    protected static function buildLookUpHost($hostname, $blacklist)
    {
        return $hostname . '.' . $blacklist;
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
    protected static function reverseIp($ip)
    {
        return implode('.', array_reverse(explode('.', $ip)));
    }
}
