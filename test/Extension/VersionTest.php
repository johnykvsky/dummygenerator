<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\Randomizer\XoshiroRandomizer;
use DummyGenerator\Definitions\Extension\VersionExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Version;
use PHPUnit\Framework\TestCase;

class VersionTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);

        $container->add(VersionExtensionInterface::class, Version::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testSemver(): void
    {
        self::assertCount(3, explode('.', $this->generator->semver()));
    }

    public function testSemverPreReleaseAndBuildShortSyntax(): void
    {
        $this->generator->addDefinition(RandomizerInterface::class, new XoshiroRandomizer(seed: 8));

        self::assertNotEmpty($this->generator->semver(preRelease: true, build: true));
    }

    public function testSemverPreReleaseAndBuildLongSyntax(): void
    {
        $this->generator->addDefinition(RandomizerInterface::class, new XoshiroRandomizer(seed: 9));

        self::assertNotEmpty($this->generator->semver(preRelease: true, build: true));
    }
}
