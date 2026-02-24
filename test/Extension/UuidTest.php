<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Uuid;
use DummyGenerator\Definitions\Extension\UuidExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);

        $container->set(UuidExtensionInterface::class, Uuid::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testUuidLength(): void
    {
        $uuid = $this->generator->uuid4();
        self::assertEquals(36, strlen($uuid));
    }

    public function testUuidFormat(): void
    {
        $uuid = $this->generator->uuid4();

        // UUID v4 format: xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
        // Dashes at positions 8, 13, 18, 23
        self::assertEquals('-', $uuid[8]);
        self::assertEquals('-', $uuid[13]);
        self::assertEquals('-', $uuid[18]);
        self::assertEquals('-', $uuid[23]);
    }

    public function testUuidVersionField(): void
    {
        $uuid = $this->generator->uuid4();

        // Character at position 14 should be '4' (version 4)
        self::assertEquals('4', $uuid[14]);
    }

    public function testUuidVariantField(): void
    {
        $uuid = $this->generator->uuid4();

        // Character at position 19 should be '8', '9', 'a', or 'b' (RFC 4122 variant)
        $variantChar = strtolower($uuid[19]);
        self::assertContains($variantChar, ['8', '9', 'a', 'b']);
    }

    public function testUuidContainsOnlyValidCharacters(): void
    {
        $uuid = $this->generator->uuid4();

        // Remove dashes and check if remaining characters are hexadecimal
        $hexPart = str_replace('-', '', $uuid);
        self::assertMatchesRegularExpression('/^[0-9a-f]{32}$/i', $hexPart);
    }

    public function testUuidFullFormatValidation(): void
    {
        $uuid = $this->generator->uuid4();

        // Full UUID v4 regex pattern
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        self::assertMatchesRegularExpression($pattern, $uuid);
    }

    public function testUuidUniqueness(): void
    {
        $uuids = [];

        // Generate 1000 UUIDs
        for ($i = 0; $i < 1000; $i++) {
            $uuids[] = $this->generator->uuid4();
        }

        // All should be unique
        $uniqueUuids = array_unique($uuids);
        self::assertCount(1000, $uniqueUuids, 'All 1000 UUIDs should be unique');
    }

    public function testUuidDifferentEachTime(): void
    {
        $uuid1 = $this->generator->uuid4();
        $uuid2 = $this->generator->uuid4();
        $uuid3 = $this->generator->uuid4();

        self::assertNotEquals($uuid1, $uuid2);
        self::assertNotEquals($uuid2, $uuid3);
        self::assertNotEquals($uuid1, $uuid3);
    }

    public function testUuidStructureConsistency(): void
    {
        // Test multiple UUIDs to ensure consistent structure
        for ($i = 0; $i < 10; $i++) {
            $uuid = $this->generator->uuid4();

            // Check length
            self::assertEquals(36, strlen($uuid), "UUID $uuid should be 36 characters");

            // Check dashes
            self::assertEquals('-', $uuid[8], "UUID $uuid should have dash at position 8");
            self::assertEquals('-', $uuid[13], "UUID $uuid should have dash at position 13");
            self::assertEquals('-', $uuid[18], "UUID $uuid should have dash at position 18");
            self::assertEquals('-', $uuid[23], "UUID $uuid should have dash at position 23");

            // Check version
            self::assertEquals('4', $uuid[14], "UUID $uuid should be version 4");

            // Check variant
            $variantChar = strtolower($uuid[19]);
            self::assertContains($variantChar, ['8', '9', 'a', 'b'], "UUID $uuid should have valid variant");
        }
    }

    /**
     * Test that UUID segments have correct lengths and hexadecimal format.
     *
     * UUID v4 format is: xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
     * This breaks down into 5 segments with lengths: 8-4-4-4-12
     *
     * @param int $segmentIndex The segment position (0-4)
     * @param int $expectedLength Expected character count for this segment
     */
    #[DataProvider('uuidSegmentProvider')]
    public function testUuidSegmentLength(int $segmentIndex, int $expectedLength): void
    {
        $uuid = $this->generator->uuid4();
        $segments = explode('-', $uuid);

        self::assertArrayHasKey($segmentIndex, $segments);
        self::assertEquals($expectedLength, strlen($segments[$segmentIndex]));
        self::assertMatchesRegularExpression('/^[0-9a-f]+$/i', $segments[$segmentIndex]);
    }

    /**
     * Data provider for UUID segment validation.
     *
     * @return array<string, array{0: int, 1: int}>
     */
    public static function uuidSegmentProvider(): array
    {
        return [
            'segment 0' => [0, 8],
            'segment 1' => [1, 4],
            'segment 2' => [2, 4],
            'segment 3' => [3, 4],
            'segment 4' => [4, 12],
        ];
    }

    /**
     * Test that UUID has exactly 5 segments separated by dashes.
     */
    public function testUuidHasCorrectNumberOfSegments(): void
    {
        $uuid = $this->generator->uuid4();
        $segments = explode('-', $uuid);

        self::assertCount(5, $segments, 'UUID should have exactly 5 segments');
    }
}
