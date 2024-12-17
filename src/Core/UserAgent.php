<?php

declare(strict_types=1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\UserAgentExtensionInterface;

class UserAgent implements UserAgentExtensionInterface, RandomizerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;

    /**
     * @var string[]
     */
    protected array $userAgents = ['firefox', 'chrome', 'internetExplorer', 'opera', 'safari', 'edge'];

    /**
     * @var string[]
     */
    protected array $windowsPlatformTokens = [
        'Windows NT 6.2', 'Windows NT 6.1', 'Windows NT 6.0', 'Windows NT 5.2', 'Windows NT 5.1',
        'Windows NT 5.01', 'Windows NT 5.0', 'Windows NT 4.0', 'Windows 98; Win 9x 4.90', 'Windows 98',
        'Windows 95', 'Windows CE',
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
     * Mac processors (it also added U;)
     */
    protected array $macProcessor = ['Intel', 'PPC', 'U; Intel', 'U; PPC'];

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
        $saf = $this->randomizer->getInt(531, 536) . $this->randomizer->getInt(0, 2);

        $platforms = [
            '(' . $this->linuxPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/" . $this->randomizer->getInt(36, 40) . '.0.'
                . $this->randomizer->getInt(800, 899) . ".0 Mobile Safari/$saf",
            '(' . $this->windowsPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/" . $this->randomizer->getInt(36, 40) . '.0.'
                . $this->randomizer->getInt(800, 899) . ".0 Mobile Safari/$saf",
            '(' . $this->macPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/" . $this->randomizer->getInt(36, 40) . '.0.'
                . $this->randomizer->getInt(800, 899) . ".0 Mobile Safari/$saf",
        ];

        return 'Mozilla/5.0 ' . $this->randomizer->randomElement($platforms);
    }

    public function edge(): string
    {
        $saf = $this->randomizer->getInt(531, 537) . '.' . $this->randomizer->getInt(0, 2);
        $chrv = $this->randomizer->getInt(79, 99) . '.0';

        $platforms = [
            '(' . $this->windowsPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/$chrv" . '.' . $this->randomizer->getInt(4000, 4844)
                . '.' . $this->randomizer->getInt(10, 99) . " Safari/$saf Edg/$chrv" . $this->randomizer->getInt(1000, 1146) . '.'
                . $this->randomizer->getInt(0, 99),
            '(' . $this->macPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/$chrv" . '.' . $this->randomizer->getInt(4000, 4844)
                . '.' . $this->randomizer->getInt(10, 99) . " Safari/$saf Edg/$chrv" . $this->randomizer->getInt(1000, 1146)
                . '.' . $this->randomizer->getInt(0, 99),
            '(' . $this->linuxPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Chrome/$chrv" . '.' . $this->randomizer->getInt(4000, 4844)
                . '.' . $this->randomizer->getInt(10, 99) . " Safari/$saf EdgA/$chrv" . $this->randomizer->getInt(1000, 1146)
                . '.' . $this->randomizer->getInt(0, 99),
            '(' . $this->iosMobileToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Version/15.0 EdgiOS/$chrv" . $this->randomizer->getInt(1000, 1146)
                . '.' . $this->randomizer->getInt(0, 99) . " Mobile/15E148 Safari/$saf",
        ];

        return 'Mozilla/5.0 ' . $this->randomizer->randomElement($platforms);
    }

    public function firefox(): string
    {
        $ver = 'Gecko/' . date('Ymd', $this->randomizer->getInt(strtotime('2010-1-1'), time())) . ' Firefox/'
            . $this->randomizer->getInt(35, 37) . '.0';

        $platforms = [
            '(' . $this->windowsPlatformToken() . '; ' . $this->randomizer->randomElement($this->lang) . '; rv:1.9.' . $this->randomizer->getInt(0, 2)
                . '.20) ' . $ver,
            '(' . $this->linuxPlatformToken() . '; rv:' . $this->randomizer->getInt(5, 7) . '.0) ' . $ver,
            '(' . $this->macPlatformToken() . ' rv:' . $this->randomizer->getInt(2, 6) . '.0) ' . $ver,
        ];

        return 'Mozilla/5.0 ' . $this->randomizer->randomElement($platforms);
    }

    public function safari(): string
    {
        $saf = $this->randomizer->getInt(531, 535) . '.' . $this->randomizer->getInt(1, 50) . '.' . $this->randomizer->getInt(1, 7);

        if ($this->randomizer->getBool()) {
            $ver = $this->randomizer->getInt(4, 5) . '.' . $this->randomizer->getInt(0, 1);
        } else {
            $ver = $this->randomizer->getInt(4, 5) . '.0.' . $this->randomizer->getInt(1, 5);
        }

        $mobileDevices = [
            'iPhone; CPU iPhone OS',
            'iPad; CPU OS',
        ];

        $platforms = [
            '(Windows; U; ' . $this->windowsPlatformToken() . ") AppleWebKit/$saf (KHTML, like Gecko) Version/$ver Safari/$saf",
            '(' . $this->macPlatformToken() . ' rv:' . $this->randomizer->getInt(2, 6) . '.0; ' . $this->randomizer->randomElement($this->lang)
                . ") AppleWebKit/$saf (KHTML, like Gecko) Version/$ver Safari/$saf",
            '(' . $this->randomizer->randomElement($mobileDevices) . ' ' . $this->randomizer->getInt(7, 8) . '_' . $this->randomizer->getInt(0, 2)
                . '_' . $this->randomizer->getInt(1, 2) . ' like Mac OS X; ' . $this->randomizer->randomElement($this->lang)
                . ") AppleWebKit/$saf (KHTML, like Gecko) Version/" . $this->randomizer->getInt(3, 4) . '.0.5 Mobile/8B'
                . $this->randomizer->getInt(111, 119) . " Safari/6$saf",
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
}
