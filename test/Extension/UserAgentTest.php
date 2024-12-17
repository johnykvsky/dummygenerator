<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\UserAgent;
use DummyGenerator\Definitions\Extension\UserAgentExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class UserAgentTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(UserAgentExtensionInterface::class, UserAgent::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testUserAgent(): void
    {
        self::assertNotEmpty($this->generator->userAgent());
    }

    public function testChrome(): void
    {
        self::assertStringContainsString('Chrome', $this->generator->chrome());
    }

    public function testEdge(): void
    {
        self::assertStringContainsString('Edg', $this->generator->edge());
    }

    public function testFirefox(): void
    {
        self::assertStringContainsString('Firefox', $this->generator->firefox());
    }

    public function testSafari(): void
    {
        self::assertStringContainsString('Safari', $this->generator->safari());
    }

    public function testOpera(): void
    {
        self::assertStringContainsString('Opera', $this->generator->opera());
    }

    public function testInternetExplorer(): void
    {
        self::assertStringContainsString('MSIE', $this->generator->internetExplorer());
    }

    public function testWindowsPlatformToken(): void
    {
        self::assertStringContainsString('Windows', $this->generator->windowsPlatformToken());
    }

    public function testMacPlatformToken(): void
    {
        self::assertStringContainsString('Macintosh', $this->generator->macPlatformToken());
    }

    public function testIosMobileToken(): void
    {
        self::assertStringContainsString('iPhone', $this->generator->iosMobileToken());
    }

    public function testAndroidMobileToken(): void
    {
        self::assertStringContainsString('Android', $this->generator->androidMobileToken());
    }

    public function testLinuxPlatformToken(): void
    {
        self::assertStringContainsString('Linux', $this->generator->linuxPlatformToken());
    }
}
