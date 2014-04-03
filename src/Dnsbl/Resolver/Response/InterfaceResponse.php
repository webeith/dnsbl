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
 * Net DNS resolver response interface
 *
 * @author Webeith <webeith@gmail.com>
 */
interface InterfaceResponse
{

    /**
     * Check server is listed
     *
     * @return bool
     */
    public function isListed();

    /**
     * Set server as listed
     *
     * @param bool $listed
     *
     * @return InterfaceResponse
     */
    public function listed($listed = true);

    /**
     * Gets the value of answer
     *
     * @return string
     */
    public function getAnswer();

    /**
     * Sets the value of answer
     *
     * @param string $answer
     *
     * @return InterfaceResponse
     */
    public function setAnswer($answer);

    /**
     * Gets the value of query
     *
     * @return string
     */
    public function getQuery();

    /**
     * Sets the value of query
     *
     * @param string $query
     *
     * @return InterfaceResponse
     */
    public function setQuery($query);

    /**
     * Gets the value of server
     *
     * @return Server
     */
    public function getServer();

    /**
     * Sets the value of server
     *
     * @param Server $server
     *
     * @return InterfaceResponse
     */
    public function setServer(Server $server);
}
