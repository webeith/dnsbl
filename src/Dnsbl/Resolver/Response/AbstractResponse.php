<?php

/*
 * This file is part of the DNSBL package.
 * (c) Webeith <webeith@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dnsbl\Resolver\Response;

use Dnsbl\BL\Server;

/**
 * Net DNS resolver response
 *
 * @author Webeith <webeith@gmail.com>
 */
abstract class AbstractResponse implements InterfaceResponse
{
    /**
     * @var bool
     */
    protected $isListed = false;

    /**
     * @var string
     */
    protected $answer;

    /**
     * @var string
     */
    protected $query;

    /**
     * @var string
     */
    protected $hostname;

    /**
     * Check host is listed
     *
     * @return bool
     */
    public function isListed()
    {
        return $this->isListed;
    }

    /**
     * Set host as listed
     *
     * @param bool $listed
     *
     * @return NetDnsResponse
     */
    public function listed($listed = true)
    {
        $this->isListed = $listed;

        return $this;
    }

    /**
     * Gets the value of answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Sets the value of answer
     *
     * @param string $answer
     *
     * @return NetDnsResponse
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Gets the value of query
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Sets the value of query
     *
     * @param string $query
     *
     * @return NetDnsResponse
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Gets the value of server
     *
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Sets the value of server
     *
     * @param Server $server
     *
     * @return NetDnsResponse
     */
    public function setServer(Server $server)
    {
        $this->server = $server;

        return $this;
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
     * @param string $hostname
     *
     * @return AbstractResponse
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }
}
