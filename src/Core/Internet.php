<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Exception\ExtensionRuntimeException;
use DummyGenerator\Definitions\Extension\InternetExtensionInterface;

class Internet implements
    InternetExtensionInterface,
    GeneratorAwareExtensionInterface,
    RandomizerAwareExtensionInterface,
    ReplacerAwareExtensionInterface
{
    use GeneratorAwareExtensionTrait;
    use RandomizerAwareExtensionTrait;
    use ReplacerAwareExtensionTrait;

    /** @var string[] */
    protected array $freeEmailDomain = ['gmail.com', 'yahoo.com', 'hotmail.com'];

    /** @var string[] */
    protected array $tld = ['com', 'com', 'com', 'com', 'com', 'com', 'biz', 'info', 'net', 'org'];

    /** @var string[] */
    protected array $userNameFormats = [
        '{{lastName}}.{{firstName}}',
        '{{firstName}}.{{lastName}}',
        '{{firstName}}##',
        '?{{lastName}}',
    ];

    /** @var string[] */
    protected array $emailFormats = [
        '{{userName}}@{{domainName}}',
        '{{userName}}@{{freeEmailDomain}}',
    ];

    /** @var string[] */
    protected array $urlFormats = [
        'http://www.{{domainName}}/',
        'http://{{domainName}}/',
        'http://www.{{domainName}}/{{slug}}',
        'http://www.{{domainName}}/{{slug}}',
        'https://www.{{domainName}}/{{slug}}',
        'http://www.{{domainName}}/{{slug}}.html',
        'http://{{domainName}}/{{slug}}',
        'http://{{domainName}}/{{slug}}',
        'http://{{domainName}}/{{slug}}.html',
        'https://{{domainName}}/{{slug}}.html',
    ];

    /**
     * @var array<int,array<string>>
     *
     * @see https://tools.ietf.org/html/rfc1918#section-3
     */
    protected array $localIpBlocks = [
        ['10.0.0.0', '10.255.255.255'],
        ['172.16.0.0', '172.31.255.255'],
        ['192.168.0.0', '192.168.255.255'],
    ];

    public function email(): string
    {
        $format = $this->randomizer->randomElement($this->emailFormats);

        return $this->generator->parse($format);
    }

    public function safeEmail(): string
    {
        return preg_replace('/\s/u', '', $this->userName() . '@' . $this->safeEmailDomain()) ?? '';
    }

    public function freeEmail(): string
    {
        return preg_replace('/\s/u', '', $this->userName() . '@' . $this->freeEmailDomain()) ?? '';
    }

    public function companyEmail(): string
    {
        return preg_replace('/\s/u', '', $this->userName() . '@' . $this->domainName()) ?? '';
    }

    public function freeEmailDomain(): string
    {
        return $this->randomizer->randomElement($this->freeEmailDomain);
    }

    public function safeEmailDomain(): string
    {
        $domains = [
            'example.com',
            'example.org',
            'example.net',
        ];

        return $this->randomizer->randomElement($domains);
    }

    public function userName(): string
    {
        $format = $this->randomizer->randomElement($this->userNameFormats);
        $username = $this->replacer->bothify($this->generator->parse($format));
        $username = $this->replacer->toLower($this->replacer->transliterate($username));

        // check if transliterate() didn't support the language and removed all letters
        if (trim($username, '._') === '') {
            throw new ExtensionRuntimeException('userName failed with the selected locale. Try a different locale or activate the "intl" PHP extension.');
        }

        // clean possible trailing dots from first/last names
        $username = str_replace('..', '.', $username);
        return rtrim($username, '.');
    }

    public function password(int $minLength = 6, int $maxLength = 20): string
    {
        $pattern = str_repeat('?', $this->randomizer->getInt($minLength, $maxLength));

        return $this->replacer->lexify($pattern, true);
    }

    public function domainName(): string
    {
        return $this->domainWord() . '.' . $this->tld();
    }

    public function domainWord(): string
    {
        // @phpstan-ignore-next-line
        $lastName = $this->generator->lastName();

        $lastName = $this->replacer->toLower($this->replacer->transliterate($lastName));

        // check if transliterate() didn't support the language and removed all letters
        if (trim($lastName, '._') === '') {
            throw new ExtensionRuntimeException('domainWord failed with the selected locale. Try a different locale or activate the "intl" PHP extension.');
        }

        // clean possible trailing dot from last name
        return rtrim($lastName, '.');
    }

    public function tld(): string
    {
        return $this->randomizer->randomElement($this->tld);
    }

    public function url(): string
    {
        $format = $this->randomizer->randomElement($this->urlFormats);

        return $this->generator->parse($format);
    }

    public function slug(int $nbWords = 6, bool $variableNbWords = true): string
    {
        if ($nbWords <= 0) {
            return '';
        }

        if ($variableNbWords) {
            $nbWords = (int) ($nbWords * $this->randomizer->getInt(60, 140) / 100) + 1;
        }

        // @phpstan-ignore-next-line
        $words = $this->generator->words($nbWords);

        return implode('-', $words);
    }

    public function ipv4(): string
    {
        $ipv4 = long2ip($this->randomizer->getBool() ? $this->randomizer->getInt(-2147483648, -2) : $this->randomizer->getInt(16777216, 2147483647));

        // @phpstan-ignore-next-line
        if ($ipv4 === false) {
            throw new ExtensionRuntimeException('IPv4 failed with the selected data.');
        }

        return $ipv4;
    }

    public function ipv6(): string
    {
        $res = [];

        for ($i = 0; $i < 8; ++$i) {
            $res[] = dechex($this->randomizer->getInt(0, 65535));
        }

        return implode(':', $res);
    }

    public function localIpv4(): string
    {
        $ipBlock = $this->randomizer->randomElement($this->localIpBlocks);

        $localIpv4 = long2ip($this->randomizer->getInt((int) ip2long($ipBlock[0]), (int) ip2long($ipBlock[1])));

        // @phpstan-ignore-next-line
        if ($localIpv4 === false) {
            throw new ExtensionRuntimeException('IPv4 failed with the selected data.');
        }

        return $localIpv4;
    }

    public function macAddress(): string
    {
        $mac = [];

        for ($i = 0; $i < 6; ++$i) {
            $mac[] = sprintf('%02X', $this->randomizer->getInt(0, 0xff));
        }

        return implode(':', $mac);
    }
}
