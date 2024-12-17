<?php

declare(strict_types=1);

namespace Provider\pl_PL;

use DummyGenerator\Core\Internet as BaseInternet;

class Internet extends BaseInternet
{
    /**
     * @var array<string>
     */
    protected array $freeEmailDomain = ['gmail.com', 'yahoo.com', 'wp.pl', 'onet.pl', 'interia.pl', 'gazeta.pl'];
    /**
     * @var array<string>
     */
    protected array $tld = ['pl', 'pl', 'pl', 'pl', 'pl', 'pl', 'com', 'info', 'net', 'org', 'com.pl', 'com.pl', 'co.pl', 'net.pl', 'org.pl'];
}
