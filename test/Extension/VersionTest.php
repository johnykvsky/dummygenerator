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

        $this->generator = $this->generator();
    }

    public function testSemver(): void
    {
        self::assertCount(3, explode('.', $this->generator->semver()));
    }

    public function testSemverPreReleaseAndBuildShortSyntax(): void
    {
        $generator = $this->generator(8);

        self::assertNotEmpty($generator->semver(preRelease: true, build: true));
    }

    public function testSemverPreReleaseAndBuildLongSyntax(): void
    {
        $generator = $this->generator(9);

        self::assertNotEmpty($generator->semver(preRelease: true, build: true));
    }

    private function generator(?int $seed = null): DummyGenerator
    {
        $container = new DefinitionContainer([]);

        if ($seed !== null) {
            $container->add(RandomizerInterface::class, new XoshiroRandomizer(seed: $seed));
        } else {
            $container->add(RandomizerInterface::class, Randomizer::class);
        }

        $container->add(VersionExtensionInterface::class, Version::class);
        return new DummyGenerator($container);
    }
}
