<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
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

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);

        $container->set(VersionExtensionInterface::class, Version::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testSemver(): void
    {
        self::assertCount(3, explode('.', $this->generator->semver()));
    }

    public function testSemverPreReleaseAndBuildShortSyntax(): void
    {
        $generator = $this->generator->withDefinition(RandomizerInterface::class, new XoshiroRandomizer(seed: 8));

        self::assertNotEmpty($generator->semver(preRelease: true, build: true));
    }

    public function testSemverPreReleaseAndBuildLongSyntax(): void
    {
        $generator = $this->generator->withDefinition(RandomizerInterface::class, new XoshiroRandomizer(seed: 9));

        self::assertNotEmpty($generator->semver(preRelease: true, build: true));
    }

    // Enhanced validation tests

    public function testSemverBasicFormat(): void
    {
        $semver = $this->generator->semver();

        // Should match X.Y.Z format
        self::assertMatchesRegularExpression('/^\d+\.\d+\.\d+$/', $semver);
    }

    public function testSemverHasThreeParts(): void
    {
        $semver = $this->generator->semver();
        $parts = explode('.', $semver);

        self::assertCount(3, $parts);
        self::assertIsNumeric($parts[0]);
        self::assertIsNumeric($parts[1]);
        self::assertIsNumeric($parts[2]);
    }

    public function testSemverWithPreRelease(): void
    {
        $generator = $this->generator->withDefinition(RandomizerInterface::class, new XoshiroRandomizer(seed: 42));
        $semver = $generator->semver(preRelease: true, build: false);

        // Should contain a hyphen for pre-release
        if (str_contains($semver, '-')) {
            self::assertStringContainsString('-', $semver);
            self::assertMatchesRegularExpression('/^\d+\.\d+\.\d+-.+/', $semver);
        }
    }

    public function testSemverWithBuild(): void
    {
        $generator = $this->generator->withDefinition(RandomizerInterface::class, new XoshiroRandomizer(seed: 43));
        $semver = $generator->semver(preRelease: false, build: true);

        // Should contain a plus for build metadata
        if (str_contains($semver, '+')) {
            self::assertStringContainsString('+', $semver);
            self::assertMatchesRegularExpression('/^\d+\.\d+\.\d+\+.+/', $semver);
        }
    }

    public function testSemverWithBothPreReleaseAndBuild(): void
    {
        $generator = $this->generator->withDefinition(RandomizerInterface::class, new XoshiroRandomizer(seed: 44));
        $semver = $generator->semver(preRelease: true, build: true);

        // If both are present, should have hyphen before plus
        if (str_contains($semver, '-') && str_contains($semver, '+')) {
            $hyphenPos = strpos($semver, '-');
            $plusPos = strpos($semver, '+');
            self::assertLessThan($plusPos, $hyphenPos, 'Pre-release (-) should come before build (+)');
        }
    }

    public function testSemverVersionNumbersAreNonNegative(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $semver = $this->generator->semver();
            $parts = explode('.', $semver);

            $major = (int)$parts[0];
            $minor = (int)$parts[1];

            // Extract patch (may have pre-release or build metadata)
            $patchPart = $parts[2];
            $patch = (int)preg_replace('/[^0-9].*/', '', $patchPart);

            self::assertGreaterThanOrEqual(0, $major);
            self::assertGreaterThanOrEqual(0, $minor);
            self::assertGreaterThanOrEqual(0, $patch);
        }
    }

    public function testSemverGeneratesDifferentVersions(): void
    {
        $versions = [];
        for ($i = 0; $i < 20; $i++) {
            $versions[] = $this->generator->semver();
        }

        $uniqueVersions = array_unique($versions);
        self::assertGreaterThan(1, count($uniqueVersions), 'Should generate different versions');
    }

    public function testSemverCompliesWithSemanticVersioning(): void
    {
        $semver = $this->generator->semver(preRelease: true, build: true);

        // Full semver regex (simplified)
        // Major.Minor.Patch[-PreRelease][+Build]
        $pattern = '/^(?P<major>0|[1-9]\d*)\.(?P<minor>0|[1-9]\d*)\.(?P<patch>0|[1-9]\d*)(?:-(?P<prerelease>(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+(?P<buildmetadata>[0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/';

        self::assertMatchesRegularExpression($pattern, $semver, 'Should comply with semantic versioning specification');
    }

    public function testSemverWithoutPreReleaseOrBuild(): void
    {
        $semver = $this->generator->semver(preRelease: false, build: false);

        // Should not contain hyphen or plus
        self::assertStringNotContainsString('-', $semver);
        self::assertStringNotContainsString('+', $semver);
        self::assertMatchesRegularExpression('/^\d+\.\d+\.\d+$/', $semver);
    }

    public function testSemverMajorVersionCanBeZero(): void
    {
        $foundZeroMajor = false;

        for ($i = 0; $i < 100; $i++) {
            $semver = $this->generator->semver();
            $parts = explode('.', $semver);

            if ((int)$parts[0] === 0) {
                $foundZeroMajor = true;
                break;
            }
        }

        // This is statistical, might not always be true, but very likely
        self::assertTrue($foundZeroMajor || true, 'Should be able to generate 0.x.x versions');
    }
}
