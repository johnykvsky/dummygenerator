<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\UserAgentExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

class UserAgent implements UserAgentExtensionInterface
{
    public function __construct(
        protected RandomizerInterface $randomizer
    ) {
    }

    /** @var string[] */
    protected array $userAgents = ['firefox', 'chrome', 'internetExplorer', 'opera', 'safari', 'edge'];

    /** @var string[] */
    protected array $windowsPlatformTokens = [
        'Windows NT 10.0; Win64; x64',
        'Windows NT 10.0; WOW64',
        'Windows NT 6.3; Win64; x64',
        'Windows NT 6.2; Win64; x64',
        'Windows NT 6.1; Win64; x64',
    ];

    /**
     * @var string[]
     *
     * Possible processors on Linux
     */
    protected array $linuxProcessor = ['i686', 'x86_64'];

    /**
     * @var string[]
     *
     * Mac processors
     */
    protected array $macProcessor = ['Intel', 'Intel; Mac OS X 10_15_7', 'Intel; Mac OS X 14_4_1'];

    /**
     * @var string[]
     *
     * Add as many languages as you like.
     */
    protected array $lang = ['en-US', 'sl-SI', 'nl-NL'];

    public function userAgent(): string
    {
        $userAgentName = $this->randomizer->randomElement($this->userAgents);

        return $this->$userAgentName();
    }

    public function chrome(): string
    {
        $major = $this->randomizer->getInt(120, 135);
        $build = $this->randomizer->getInt(6000, 6800);
        $patch = $this->randomizer->getInt(0, 200);

        $platforms = [
            '(' . $this->linuxPlatformToken() . ") AppleWebKit/537.36 (KHTML, like Gecko) Chrome/$major.0.$build.$patch Safari/537.36",
            '(' . $this->windowsPlatformToken() . ") AppleWebKit/537.36 (KHTML, like Gecko) Chrome/$major.0.$build.$patch Safari/537.36",
            '(' . $this->macPlatformToken() . ") AppleWebKit/537.36 (KHTML, like Gecko) Chrome/$major.0.$build.$patch Safari/537.36",
        ];

        return 'Mozilla/5.0 ' . $this->randomizer->randomElement($platforms);
    }

    public function edge(): string
    {
        $saf = '537.36';
        $chrv = $this->randomizer->getInt(120, 135) . '.0';

        $platforms = [
            '(' . $this->windowsPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/$chrv" . '.' . $this->randomizer->getInt(6000, 6800)
                . '.' . $this->randomizer->getInt(10, 99) . " Safari/$saf Edg/$chrv" . $this->randomizer->getInt(2000, 2600) . '.'
                . $this->randomizer->getInt(0, 99),
            '(' . $this->macPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/$chrv" . '.' . $this->randomizer->getInt(6000, 6800)
                . '.' . $this->randomizer->getInt(10, 99) . " Safari/$saf Edg/$chrv" . $this->randomizer->getInt(2000, 2600)
                . '.' . $this->randomizer->getInt(0, 99),
            '(' . $this->linuxPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/$chrv" . '.' . $this->randomizer->getInt(6000, 6800)
                . '.' . $this->randomizer->getInt(10, 99) . " Safari/$saf EdgA/$chrv" . $this->randomizer->getInt(2000, 2600)
                . '.' . $this->randomizer->getInt(0, 99),
            '(' . $this->iosMobileToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Version/17.0 EdgiOS/$chrv" . $this->randomizer->getInt(2000, 2600)
                . '.' . $this->randomizer->getInt(0, 99) . " Mobile/15E148 Safari/$saf",
        ];

        return 'Mozilla/5.0 ' . $this->randomizer->randomElement($platforms);
    }

    public function firefox(): string
    {
        $major = $this->randomizer->getInt(120, 135);
        $ver = "Gecko/20100101 Firefox/$major.0";

        $platforms = [
            '(' . $this->windowsPlatformToken() . '; rv:' . $major . '.0) ' . $ver,
            '(' . $this->linuxPlatformToken() . '; rv:' . $major . '.0) ' . $ver,
            '(' . $this->macPlatformToken() . '; rv:' . $major . '.0) ' . $ver,
        ];

        return 'Mozilla/5.0 ' . $this->randomizer->randomElement($platforms);
    }

    public function safari(): string
    {
        $saf = '605.1.15';
        $ver = $this->randomizer->getInt(16, 17) . '.' . $this->randomizer->getInt(0, 5);

        $mobileDevices = [
            'iPhone; CPU iPhone OS',
            'iPad; CPU OS',
        ];

        $platforms = [
            '(' . $this->macPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Version/$ver Safari/$saf",
            '(' . $this->randomizer->randomElement($mobileDevices) . ' ' . $this->randomizer->getInt(16, 17) . '_' . $this->randomizer->getInt(0, 5)
                . ' like Mac OS X) AppleWebKit/' . $saf . ' (KHTML, like Gecko) Version/' . $ver . ' Mobile/15E148 Safari/604.1',
        ];

        return 'Mozilla/5.0 ' . $this->randomizer->randomElement($platforms);
    }

    public function opera(): string
    {
        $platforms = [
            '(' . $this->linuxPlatformToken() . '; ' . $this->randomizer->randomElement($this->lang) . ') Presto/2.' . $this->randomizer->getInt(8, 12)
                . '.' . $this->randomizer->getInt(160, 355) . ' Version/' . $this->randomizer->getInt(10, 12) . '.00',
            '(' . $this->windowsPlatformToken() . '; ' . $this->randomizer->randomElement($this->lang) . ') Presto/2.' . $this->randomizer->getInt(8, 12)
                . '.' . $this->randomizer->getInt(160, 355) . ' Version/' . $this->randomizer->getInt(10, 12) . '.00',
        ];

        return 'Opera/' . $this->randomizer->getInt(8, 9) . '.' . $this->randomizer->getInt(10, 99) . ' ' . $this->randomizer->randomElement($platforms);
    }

    public function internetExplorer(): string
    {
        return 'Mozilla/5.0 (compatible; MSIE ' . $this->randomizer->getInt(5, 11) . '.0; ' . $this->windowsPlatformToken() . '; Trident/'
                . $this->randomizer->getInt(3, 5) . '.' . $this->randomizer->getInt(0, 1) . ')';
    }

    public function windowsPlatformToken(): string
    {
        return $this->randomizer->randomElement($this->windowsPlatformTokens);
    }

    public function macPlatformToken(): string
    {
        return 'Macintosh; ' . $this->randomizer->randomElement($this->macProcessor) . ' Mac OS X 10_' . $this->randomizer->getInt(5, 8)
                . '_' . $this->randomizer->getInt(0, 9);
    }

    public function iosMobileToken(): string
    {
        $iosVer = $this->randomizer->getInt(13, 15) . '_' . $this->randomizer->getInt(0, 2);

        return 'iPhone; CPU iPhone OS ' . $iosVer . ' like Mac OS X';
    }

    public function androidMobileToken(): string
    {
        return 'Linux; Android ' . $this->randomizer->getInt(8, 15);
    }

    public function linuxPlatformToken(): string
    {
        return 'X11; Linux ' . $this->randomizer->randomElement($this->linuxProcessor);
    }

    /** @var string[] */
    protected array $botUserAgents = [
        'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
        'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)',
        'DuckDuckBot/1.1; (+http://duckduckgo.com/duckduckbot.html)',
        'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)',
        'Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)',
        'Twitterbot/1.0',
        'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
    ];

    public function botUserAgent(): string
    {
        return $this->randomizer->randomElement($this->botUserAgents);
    }
}
