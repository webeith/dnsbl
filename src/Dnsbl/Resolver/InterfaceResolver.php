<?php

/*
 * This file is part of the DNSBL package.
 * (c) Webeith <webeith@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dnsbl\Resolver;

use Dnsbl\BL\Server;

/**
 * Resolver interface
 *
 * @author Webeith <webeith@gmail.com>
 */
interface InterfaceResolver
{
    public function execute($hostname);

    public function setContext(Server $context);

    public function getContext();
}
