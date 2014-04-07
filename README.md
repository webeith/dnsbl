DNSBL service
=====
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/webeith/dnsbl/badges/quality-score.png?s=e91a29f1374e1a7bb12e5e908a58cdc9ba8171b1)](https://scrutinizer-ci.com/g/webeith/dnsbl/)
[![Code Coverage](https://scrutinizer-ci.com/g/webeith/dnsbl/badges/coverage.png?s=82f8a07b637ea41cb00b3f85258994e73499f6d6)](https://scrutinizer-ci.com/g/webeith/dnsbl/)
[![Build Status](https://travis-ci.org/webeith/dnsbl.png?branch=master)](http://travis-ci.org/webeith/dnsbl)

Usage Example
-------------

``` php
<?php

use Dnsbl\Dnsbl,
    Dnsbl\Resolver,
    Dnsbl\BL\Server;


$domainResolver = new Resolver\NetDnsDomainResolver();
$ipResolver = new Resolver\NetDnsIPResolver();

// Checking from url. Example how to create custom resolver.
$urlResolver = new Resolver\UrlResolver();
$urlResolver->setLocation('https://zeustracker.abuse.ch/blocklist.php?download=baddomains');

// Checking from file.
file_put_contents('zeustracker.ip.bl.file', file_get_contents('https://zeustracker.abuse.ch/blocklist.php?download=ipblocklist'));
$fileResolver = new Resolver\FileResolver('zeustracker.ip.bl.file');

$dnsbl = new Dnsbl();

$servers = array(
    new Server('zeustracker.abuse.ch',   $urlResolver,    array('domain', 'IPv4')),
    new Server('zeustracker.ip.bl.file', $fileResolver,   array('IPv4')),
    new Server('dbl.spamhaus.org',       $domainResolver, array('domain')),
    new Server('pbl.spamhaus.org',       $ipResolver,     array('IPv4'))
);

$dnsbl->setBlServers($servers);

// Checking in bl who is supported domain.
print_r($dnsbl->checkDomain('advanc320.co.vu'));

// Checking in bl who is supported IP.
print_r($dnsbl->checkIP('127.0.0.2'));

// Checking in all bl.
print_r($dnsbl->check('advanc320.co.vu'));

```

Installation
------------

``` bash
$> php composer.phar require webeith/dnsbl
```
