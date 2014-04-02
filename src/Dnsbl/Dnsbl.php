<?php

/*
 * This file is part of the DNSBL package.
 * (c) Webeith <webeith@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dnsbl;

use Dnsbl\Resolver\InterfaceResolver;

/**
 * Dnsbl service
 *
 * @author Webeith <webeith@gmail.com>
 */
class Dnsbl
{
    protected $blackLists = array();

    protected $resolver;

    public function __construct(InterfaceResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Gets the value of BlackLists
     *
     * @return array
     */
    public function getBlackLists()
    {
        return $this->blackLists;
    }

    /**
     * Sets the value of BlackLists
     *
     * @param array $blackLists
     *
     * @return Dnsbl
     */
    public function setBlackLists(array $blackLists)
    {
        $this->blackLists = $blackLists;

        return $this;
    }

    /**
     * Add the value to BlackLists
     *
     * @param string $blackList
     *
     * @return Dnsbl
     */
    public function addBlackList($blackList)
    {
        $this->blackLists[] = $blackList;

        return $this;
    }

    /**
     * Remove the value from BlackLists
     *
     * @param string $blackList
     *
     * @return Dnsbl
     */
    public function removeBlackList($blackList)
    {
        $key = array_search($blackList, $this->blackLists);

        if (isset($this->blackLists[$key])) {
            unset($this->blackLists[$key]);
        }

        return $this;
    }
}
