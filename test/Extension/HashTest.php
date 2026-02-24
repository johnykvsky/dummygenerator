<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
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

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(HashExtensionInterface::class, Hash::class);
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

    // Enhanced validation tests

    public function testMd5ContainsOnlyHexCharacters(): void
    {
        $md5 = $this->generator->md5();
        self::assertMatchesRegularExpression('/^[0-9a-f]{32}$/', $md5);
    }

    public function testSha1ContainsOnlyHexCharacters(): void
    {
        $sha1 = $this->generator->sha1();
        self::assertMatchesRegularExpression('/^[0-9a-f]{40}$/', $sha1);
    }

    public function testSha256ContainsOnlyHexCharacters(): void
    {
        $sha256 = $this->generator->sha256();
        self::assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $sha256);
    }

    public function testMd5GeneratesDifferentHashes(): void
    {
        $hashes = [];
        for ($i = 0; $i < 20; $i++) {
            $hashes[] = $this->generator->md5();
        }

        $uniqueHashes = array_unique($hashes);
        self::assertGreaterThan(1, count($uniqueHashes), 'Should generate different MD5 hashes');
    }

    public function testSha1GeneratesDifferentHashes(): void
    {
        $hashes = [];
        for ($i = 0; $i < 20; $i++) {
            $hashes[] = $this->generator->sha1();
        }

        $uniqueHashes = array_unique($hashes);
        self::assertGreaterThan(1, count($uniqueHashes), 'Should generate different SHA1 hashes');
    }

    public function testSha256GeneratesDifferentHashes(): void
    {
        $hashes = [];
        for ($i = 0; $i < 20; $i++) {
            $hashes[] = $this->generator->sha256();
        }

        $uniqueHashes = array_unique($hashes);
        self::assertGreaterThan(1, count($uniqueHashes), 'Should generate different SHA256 hashes');
    }

    public function testMd5IsLowercase(): void
    {
        $md5 = $this->generator->md5();
        self::assertEquals(strtolower($md5), $md5);
    }

    public function testSha1IsLowercase(): void
    {
        $sha1 = $this->generator->sha1();
        self::assertEquals(strtolower($sha1), $sha1);
    }

    public function testSha256IsLowercase(): void
    {
        $sha256 = $this->generator->sha256();
        self::assertEquals(strtolower($sha256), $sha256);
    }

    public function testHashesHaveCorrectLengths(): void
    {
        // Test multiple generations to ensure consistency
        for ($i = 0; $i < 10; $i++) {
            self::assertEquals(32, strlen($this->generator->md5()), 'MD5 should always be 32 characters');
            self::assertEquals(40, strlen($this->generator->sha1()), 'SHA1 should always be 40 characters');
            self::assertEquals(64, strlen($this->generator->sha256()), 'SHA256 should always be 64 characters');
        }
    }

    public function testHashesAreValidHexadecimal(): void
    {
        // Test that hashes can be interpreted as hexadecimal
        $md5 = $this->generator->md5();
        $sha1 = $this->generator->sha1();
        $sha256 = $this->generator->sha256();

        // Should not throw errors when interpreting as hex
        self::assertIsNumeric(hexdec(substr($md5, 0, 8)));
        self::assertIsNumeric(hexdec(substr($sha1, 0, 8)));
        self::assertIsNumeric(hexdec(substr($sha256, 0, 8)));
    }
}