<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Definitions\Extension\HashExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Hash;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class HashTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(HashExtensionInterface::class, Hash::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testMd5(): void
    {
        self::assertEquals(32, strlen($this->generator->md5()));
    }

    public function testSha1(): void
    {
        self::assertEquals(40, strlen($this->generator->sha1()));
    }

    public function testSha256(): void
    {
        self::assertEquals(64, strlen($this->generator->sha256()));
    }
}