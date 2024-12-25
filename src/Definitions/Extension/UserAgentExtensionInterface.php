<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface UserAgentExtensionInterface extends ExtensionInterface
{
    /** @example 'Mozilla/5.0 (Windows CE) AppleWebKit/5350 (KHTML, like Gecko) Chrome/13.0.888.0 Safari/5350' */
    public function userAgent(): string;

    /** @example 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_5) AppleWebKit/5312 (KHTML, like Gecko) Chrome/14.0.894.0 Safari/5312' */
    public function chrome(): string;

    /** @example 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.82 Safari/537.36 Edg/99.0.1150.36' */
    public function edge(): string;

    /** @example 'Mozilla/5.0 (X11; Linuxi686; rv:7.0) Gecko/20101231 Firefox/3.6' */
    public function firefox(): string;

    /** @example 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_7_1 rv:3.0; en-US) AppleWebKit/534.11.3 (KHTML, like Gecko) Version/4.0 Safari/534.11.3' */
    public function safari(): string;

    /** @example 'Opera/8.25 (Windows NT 5.1; en-US) Presto/2.9.188 Version/10.00' */
    public function opera(): string;

    /** @example 'Mozilla/5.0 (compatible; MSIE 7.0; Windows 98; Win 9x 4.90; Trident/3.0)' */
    public function internetExplorer(): string;

    /** @example 'Windows NT 6.0' */
    public function windowsPlatformToken(): string;

    /** @example 'Macintosh; PPC Mac OS X 10_6_0' */
    public function macPlatformToken(): string;

    /** @example 'iPhone; CPU iPhone OS 13_0 like Mac OS X' */
    public function iosMobileToken(): string;

    /** @example 'Linux; Android 15' */
    public function androidMobileToken(): string;

    /** @example 'X11; Linux i686' */
    public function linuxPlatformToken(): string;
}
