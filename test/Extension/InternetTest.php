<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\Internet;
use DummyGenerator\Core\Lorem;
use DummyGenerator\Core\Person;
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

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(TransliteratorInterface::class, Transliterator::class);
        $container->add(ReplacerInterface::class, Replacer::class);
        $container->add(PersonExtensionInterface::class, Person::class);
        $container->add(LoremExtensionInterface::class, Lorem::class);
        $container->add(InternetExtensionInterface::class, Internet::class);
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

}
