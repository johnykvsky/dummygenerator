<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Internet;
use DummyGenerator\Core\Lorem;
use DummyGenerator\Core\Person;
use DummyGenerator\Definitions\Extension\Exception\ExtensionRuntimeException;
use DummyGenerator\Definitions\Extension\InternetExtensionInterface;
use DummyGenerator\Definitions\Extension\LoremExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use PHPUnit\Framework\TestCase;

class InternetTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(InternetExtensionInterface::class, Internet::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testEmail(): void
    {
        self::assertStringContainsString('@', $this->generator->email());
    }

    public function testSafeEmail(): void
    {
        self::assertStringContainsString('@', $this->generator->safeEmail());
    }

    public function testFreeEmail(): void
    {
        self::assertStringContainsString('@', $this->generator->freeEmail());
    }

    public function testCompanyEmail(): void
    {
        self::assertStringContainsString('@', $this->generator->companyEmail());
    }

    public function testFreeEmailDomain(): void
    {
        self::assertStringContainsString('.', $this->generator->companyEmail());
    }

    public function testSafeEmailDomain(): void
    {
        self::assertStringContainsString('.', $this->generator->safeEmailDomain());
        self::assertStringContainsString('example', $this->generator->safeEmailDomain());
    }

    public function testUsername(): void
    {
        self::assertNotEmpty($this->generator->userName());
    }

    public function testPassword(): void
    {
        $length = strlen($this->generator->password(minLength: 3, maxLength: 8));

        self::assertTrue($length >= 3 && $length <= 8);
    }

    public function testDomainName(): void
    {
        self::assertStringContainsString('.', $this->generator->domainName());
    }

    public function testDomainWord(): void
    {
        self::assertNotEmpty($this->generator->domainWord());
    }

    public function testUserNameThrowsExtensionRuntimeExceptionWhenTransliterationRemovesAllCharacters(): void
    {
        $randomizer = $this->createMock(RandomizerInterface::class);
        $randomizer->expects(self::once())
            ->method('randomElement')
            ->willReturn('{{firstName}}##');

        $replacer = $this->createMock(ReplacerInterface::class);
        $replacer->expects(self::once())
            ->method('bothify')
            ->with('John12')
            ->willReturn('John12');
        $replacer->expects(self::once())
            ->method('transliterate')
            ->with('John12')
            ->willReturn('._');
        $replacer->expects(self::once())
            ->method('toLower')
            ->with('._')
            ->willReturn('._');

        $generator = $this->createMock(\DummyGenerator\GeneratorInterface::class);
        $generator->expects(self::once())
            ->method('parse')
            ->with('{{firstName}}##')
            ->willReturn('John12');

        $internet = new Internet($randomizer, $replacer, $generator);

        $this->expectException(ExtensionRuntimeException::class);
        $internet->userName();
    }

    public function testDomainWordThrowsExtensionRuntimeExceptionWhenTransliterationRemovesAllCharacters(): void
    {
        $randomizer = $this->createMock(RandomizerInterface::class);
        $replacer = $this->createMock(ReplacerInterface::class);
        $replacer->expects(self::once())
            ->method('transliterate')
            ->with('Doe')
            ->willReturn('._');
        $replacer->expects(self::once())
            ->method('toLower')
            ->with('._')
            ->willReturn('._');

        $generator = $this->createMock(\DummyGenerator\GeneratorInterface::class);
        $generator->expects(self::once())
            ->method('__call')
            ->with('lastName', [])
            ->willReturn('Doe');

        $internet = new Internet($randomizer, $replacer, $generator);

        $this->expectException(ExtensionRuntimeException::class);
        $internet->domainWord();
    }

    public function testTld(): void
    {
        self::assertNotEmpty($this->generator->tld());
    }

    public function testUrl(): void
    {
        self::assertStringStartsWith('http', $this->generator->url());
        self::assertStringContainsString('://', $this->generator->url());
    }

    public function testSlug(): void
    {
        self::assertCount(5, explode('-', $this->generator->slug(nbWords: 5, variableNbWords: false)));
    }

    public function testEmptySlug(): void
    {
        self::assertEquals('', $this->generator->slug(nbWords: 0));
    }

    public function testIPv4(): void
    {
        self::assertNotEmpty($this->generator->ipv4());
    }

    public function testIPv6(): void
    {
        self::assertNotEmpty($this->generator->ipv6());
    }

    public function testLocalIPv4(): void
    {
        self::assertNotEmpty($this->generator->localIpv4());
    }

    public function testMacAddress(): void
    {
        self::assertCount(6, explode(':', $this->generator->macAddress()));
    }

    // Enhanced validation tests

    public function testEmailFormat(): void
    {
        $email = $this->generator->email();

        // Should have exactly one @
        self::assertEquals(1, substr_count($email, '@'));

        // Should have at least one dot after @
        $parts = explode('@', $email);
        self::assertCount(2, $parts);
        self::assertStringContainsString('.', $parts[1]);

        // Should not have spaces
        self::assertStringNotContainsString(' ', $email);

        // Should match email pattern
        self::assertMatchesRegularExpression('/^[^@]+@[^@]+\.[^@]+$/', $email);
    }

    public function testSafeEmailDomainIsActuallySafe(): void
    {
        $domain = $this->generator->safeEmailDomain();

        // Should be one of the safe domains
        self::assertContains($domain, ['example.com', 'example.org', 'example.net']);
    }

    public function testFreeEmailDomainFromList(): void
    {
        $domain = $this->generator->freeEmailDomain();

        // Should contain a dot
        self::assertStringContainsString('.', $domain);

        // Should be a valid domain format
        self::assertMatchesRegularExpression('/^[a-z0-9.-]+\.[a-z]{2,}$/i', $domain);
    }

    public function testUsernameNoConsecutiveDots(): void
    {
        // Test multiple usernames to ensure no consecutive dots
        for ($i = 0; $i < 20; $i++) {
            $username = $this->generator->userName();
            self::assertStringNotContainsString('..', $username, "Username should not have consecutive dots: $username");
        }
    }

    public function testUsernameNoTrailingDot(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $username = $this->generator->userName();
            self::assertStringEndsNotWith('.', $username, "Username should not end with dot: $username");
        }
    }

    public function testUsernameFormat(): void
    {
        $username = $this->generator->userName();

        // Should be lowercase alphanumeric with dots and underscores only
        self::assertMatchesRegularExpression('/^[a-z0-9_.]+$/', $username);

        // Should not be empty
        self::assertNotEmpty($username);

        // Should have some content
        self::assertGreaterThan(0, strlen($username));
    }

    public function testPasswordLengthBounds(): void
    {
        // Test minimum length
        $password = $this->generator->password(minLength: 8, maxLength: 8);
        self::assertEquals(8, strlen($password));

        // Test maximum length
        $password = $this->generator->password(minLength: 20, maxLength: 20);
        self::assertEquals(20, strlen($password));

        // Test range
        $password = $this->generator->password(minLength: 10, maxLength: 15);
        $length = strlen($password);
        self::assertTrue($length >= 10 && $length <= 15);
    }

    public function testPasswordContainsAsciiCharacters(): void
    {
        $password = $this->generator->password(minLength: 10, maxLength: 10);

        // Should contain ASCII printable characters (33-126)
        for ($i = 0; $i < strlen($password); $i++) {
            $char = ord($password[$i]);
            self::assertTrue($char >= 33 && $char <= 126, "Character at position $i should be ASCII printable");
        }
    }

    public function testDomainNameValidFormat(): void
    {
        $domain = $this->generator->domainName();

        // Should contain exactly one dot (domain + TLD)
        self::assertGreaterThanOrEqual(1, substr_count($domain, '.'));

        // Should not start or end with dot
        self::assertStringStartsNotWith('.', $domain);
        self::assertStringEndsNotWith('.', $domain);

        // Should match domain pattern
        self::assertMatchesRegularExpression('/^[a-z0-9.-]+\.[a-z]{2,}$/i', $domain);
    }

    public function testUrlProtocol(): void
    {
        $url = $this->generator->url();

        // Should start with http:// or https://
        $startsWithHttp = str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
        self::assertTrue($startsWithHttp, "URL should start with http:// or https://: $url");
    }

    public function testUrlContainsProtocolAndDomain(): void
    {
        $url = $this->generator->url();

        // Should contain ://
        self::assertStringContainsString('://', $url);

        // Should have content after ://
        $parts = explode('://', $url);
        self::assertCount(2, $parts);
        self::assertNotEmpty($parts[1]);
    }

    public function testSlugFormat(): void
    {
        $slug = $this->generator->slug(nbWords: 5, variableNbWords: false);

        // Should contain hyphens
        self::assertStringContainsString('-', $slug);

        // Should be lowercase
        self::assertEquals(strtolower($slug), $slug);

        // Should have 4 hyphens for 5 words
        self::assertEquals(4, substr_count($slug, '-'));

        // Should not have spaces
        self::assertStringNotContainsString(' ', $slug);
    }

    public function testIpv4Format(): void
    {
        $ipv4 = $this->generator->ipv4();

        // Should match IPv4 pattern
        self::assertMatchesRegularExpression('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $ipv4);

        // Each octet should be 0-255
        $octets = explode('.', $ipv4);
        self::assertCount(4, $octets);

        foreach ($octets as $octet) {
            $value = (int) $octet;
            self::assertTrue($value >= 0 && $value <= 255, "Octet $octet should be 0-255");
        }
    }

    public function testIpv6Format(): void
    {
        $ipv6 = $this->generator->ipv6();

        // Should match IPv6 pattern (8 groups of hex separated by colons)
        self::assertMatchesRegularExpression('/^[0-9a-f:]+$/i', $ipv6);

        // Should have 7 colons (8 groups)
        self::assertEquals(7, substr_count($ipv6, ':'));

        // Each group should be valid hex
        $groups = explode(':', $ipv6);
        self::assertCount(8, $groups);

        foreach ($groups as $group) {
            self::assertMatchesRegularExpression('/^[0-9a-f]+$/i', $group);
        }
    }

    public function testLocalIpv4IsInPrivateRange(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $ip = $this->generator->localIpv4();

            // Convert to long for range checking
            $ipLong = ip2long($ip);

            // Check if in one of the private ranges
            $is10 = $ipLong >= ip2long('10.0.0.0') && $ipLong <= ip2long('10.255.255.255');
            $is172 = $ipLong >= ip2long('172.16.0.0') && $ipLong <= ip2long('172.31.255.255');
            $is192 = $ipLong >= ip2long('192.168.0.0') && $ipLong <= ip2long('192.168.255.255');

            self::assertTrue($is10 || $is172 || $is192, "IP $ip should be in private range");
        }
    }

    public function testMacAddressFormat(): void
    {
        $mac = $this->generator->macAddress();

        // Should match MAC address pattern
        self::assertMatchesRegularExpression('/^([0-9A-F]{2}:){5}[0-9A-F]{2}$/', $mac);

        // Each octet should be valid hex
        $octets = explode(':', $mac);
        self::assertCount(6, $octets);

        foreach ($octets as $octet) {
            self::assertEquals(2, strlen($octet));
            self::assertMatchesRegularExpression('/^[0-9A-F]{2}$/', $octet);
        }
    }

    public function testMacAddressUppercase(): void
    {
        $mac = $this->generator->macAddress();

        // Should be uppercase
        self::assertEquals(strtoupper($mac), $mac);
    }

    public function testCompanyEmailHasDomain(): void
    {
        $email = $this->generator->companyEmail();

        // Should have @ and domain
        self::assertStringContainsString('@', $email);

        $parts = explode('@', $email);
        self::assertCount(2, $parts);

        // Domain should have TLD
        self::assertStringContainsString('.', $parts[1]);

        // Should not have spaces
        self::assertStringNotContainsString(' ', $email);
    }

    public function testDomainWordIsLowercase(): void
    {
        $domainWord = $this->generator->domainWord();

        // Should be lowercase
        self::assertEquals(strtolower($domainWord), $domainWord);

        // Should not have spaces or special chars
        self::assertMatchesRegularExpression('/^[a-z0-9]+$/', $domainWord);
    }

    public function testTldIsValid(): void
    {
        $tld = $this->generator->tld();

        // Should be lowercase letters
        self::assertMatchesRegularExpression('/^[a-z]+$/', $tld);

        // Should be 2-4 characters (typical TLD length)
        self::assertTrue(strlen($tld) >= 2 && strlen($tld) <= 4);
    }

}
