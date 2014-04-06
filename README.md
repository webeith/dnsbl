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

// Basic checking
$domainResolver = new Resolver\NetDnsDomainResolver();
$ipResolver = new Resolver\NetDnsIPResolver();

// Checking from url
$urlResolver = new Resolver\UrlResolver();
$urlResolver->setLocation('https://zeustracker.abuse.ch/blocklist.php?download=baddomains');
$urlResolver->setSupportedChecks(array(Server::CHECK_DOMAIN));

// Checking from file
file_put_contents('zeustracker.ip.bl.file', file_get_contents('https://zeustracker.abuse.ch/blocklist.php?download=ipblocklist'));
$fileResolver = new Resolver\FileResolver('zeustracker.ip.bl.file', array(Server::CHECK_IPV4));

$dnsbl = new Dnsbl();

$dnsbl->addBl(new Server('dbl.spamhaus.org', array(Server::CHECK_DOMAIN), $domainResolver));
$dnsbl->addBl(new Server('pbl.spamhaus.org', array(Server::CHECK_IPV4), $ipResolver));
$dnsbl->addBl(new Server('zeustracker.abuse.ch', array(Server::CHECK_DOMAIN), $urlResolver));
$dnsbl->addBl(new Server('zeustracker.ip.bl.file', array(Server::CHECK_IPV4), $fileResolver));

foreach ($dnsbl->checkDomain('test.com') as $blackList => $result) {
    print_r($result);
}

foreach ($dnsbl->checkIP('127.0.0.2') as $blackList => $result) {
    print_r($result);
}


```

Installation
------------

``` json
    "webeith/dnsbl":  "dev-master"
```

``` bash
$> php composer.phar update
```
