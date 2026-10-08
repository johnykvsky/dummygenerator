<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\File;
use DummyGenerator\Definitions\Extension\FileExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(FileExtensionInterface::class, File::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testMimeType(): void
    {
        self::assertTrue(str_contains($this->generator->mimeType(), '/'));
    }

    public function testExtension(): void
    {
        self::assertNotEmpty($this->generator->extension());
    }

    // Enhanced validation tests

    public function testMimeTypeFormat(): void
    {
        $mimeType = $this->generator->mimeType();

        // Should match type/subtype format
        self::assertMatchesRegularExpression('/^[a-z]+\/[a-z0-9.+-]+$/i', $mimeType);

        // Should have exactly one slash
        self::assertEquals(1, substr_count($mimeType, '/'));

        // Both parts should be non-empty
        $parts = explode('/', $mimeType);
        self::assertCount(2, $parts);
        self::assertNotEmpty($parts[0]);
        self::assertNotEmpty($parts[1]);
    }

    public function testMimeTypeCommonTypes(): void
    {
        $mimeTypes = [];
        for ($i = 0; $i < 50; $i++) {
            $mimeTypes[] = $this->generator->mimeType();
        }

        // Should have variety of MIME types
        $uniqueMimeTypes = array_unique($mimeTypes);
        self::assertGreaterThan(1, count($uniqueMimeTypes), 'Should generate different MIME types');

        // At least one should be a common type category
        $types = array_map(fn($mime) => explode('/', $mime)[0], $mimeTypes);
        $commonTypes = ['text', 'image', 'video', 'audio', 'application'];
        $hasCommonType = !empty(array_intersect($types, $commonTypes));
        self::assertTrue($hasCommonType, 'Should include common MIME type categories');
    }

    public function testExtensionFormat(): void
    {
        $extension = $this->generator->extension();

        // Should be lowercase and allow hyphens/underscores
        self::assertMatchesRegularExpression('/^[a-z0-9_-]+$/', $extension);

        // Should be reasonable length (1-5 characters typically)
        self::assertTrue(strlen($extension) >= 1 && strlen($extension) <= 10);

        // Should not start with a dot
        self::assertStringStartsNotWith('.', $extension);
    }

    public function testExtensionVariety(): void
    {
        $extensions = [];
        for ($i = 0; $i < 30; $i++) {
            $extensions[] = $this->generator->extension();
        }

        // Should have variety
        $uniqueExtensions = array_unique($extensions);
        self::assertGreaterThan(1, count($uniqueExtensions), 'Should generate different file extensions');
    }

    public function testExtensionIsString(): void
    {
        $extension = $this->generator->extension();

        self::assertIsString($extension);
        self::assertNotEmpty($extension);
        self::assertGreaterThan(0, strlen($extension));
    }

    public function testMimeTypeIsString(): void
    {
        $mimeType = $this->generator->mimeType();

        self::assertIsString($mimeType);
        self::assertNotEmpty($mimeType);
        self::assertGreaterThan(2, strlen($mimeType)); // At least "a/b"
    }

    public function testExtensionLowercaseOnly(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $extension = $this->generator->extension();
            self::assertEquals(strtolower($extension), $extension, 'Extension should be lowercase');
        }
    }

    public function testMimeTypeNoWhitespace(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $mimeType = $this->generator->mimeType();
            self::assertStringNotContainsString(' ', $mimeType);
            self::assertStringNotContainsString("\t", $mimeType);
            self::assertStringNotContainsString("\n", $mimeType);
        }
    }

    public function testExtensionNoSpecialCharacters(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $extension = $this->generator->extension();
            // Should only contain lowercase letters, numbers, hyphens, and underscores
            self::assertMatchesRegularExpression('/^[a-z0-9_-]+$/', $extension);
        }
    }

    public function testMimeTypeHasValidMainType(): void
    {
        $mimeType = $this->generator->mimeType();
        $mainType = explode('/', $mimeType)[0];

        // Main type should be lowercase letters only
        self::assertMatchesRegularExpression('/^[a-z]+$/', $mainType);
    }

    public function testFileNameDefault(): void
    {
        $fileName = $this->generator->fileName();
        self::assertStringContainsString('.', $fileName);
        [$base, $ext] = explode('.', $fileName, 2);
        self::assertNotEmpty($base);
        self::assertNotEmpty($ext);
    }

    public function testFileNameWithCustomExtension(): void
    {
        $fileName = $this->generator->fileName('pdf');
        self::assertStringEndsWith('.pdf', $fileName);

        $fileNameWithDot = $this->generator->fileName('.png');
        self::assertStringEndsWith('.png', $fileNameWithDot);
        self::assertSame(1, substr_count($fileNameWithDot, '.'));
    }

    public function testFileSizeRawBytes(): void
    {
        $size = $this->generator->fileSize(100, 500);
        self::assertIsInt($size);
        self::assertGreaterThanOrEqual(100, $size);
        self::assertLessThanOrEqual(500, $size);
    }

    public function testFileSizeFormatted(): void
    {
        $sizeB = $this->generator->fileSize(100, 500, formatted: true);
        self::assertIsString($sizeB);
        self::assertMatchesRegularExpression('/^[0-9.]+\s*B$/', $sizeB);

        $sizeKb = $this->generator->fileSize(1024, 2048, formatted: true);
        self::assertIsString($sizeKb);
        self::assertMatchesRegularExpression('/^[0-9.]+\s*KB$/', $sizeKb);

        $sizeMb = $this->generator->fileSize(2 * 1048576, 5 * 1048576, formatted: true);
        self::assertIsString($sizeMb);
        self::assertMatchesRegularExpression('/^[0-9.]+\s*MB$/', $sizeMb);

        $sizeGb = $this->generator->fileSize(1073741824, 2147483648, formatted: true);
        self::assertIsString($sizeGb);
        self::assertMatchesRegularExpression('/^[0-9.]+\s*GB$/', $sizeGb);
    }

    public function testFileSizeInvalidArgumentsThrow(): void
    {
        $this->expectException(\DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException::class);
        $this->generator->fileSize(minBytes: 500, maxBytes: 100);
    }

    public function testFileSizeNegativeMinThrows(): void
    {
        $this->expectException(\DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException::class);
        $this->generator->fileSize(minBytes: -1, maxBytes: 100);
    }

    public function testMimeTypeForExtension(): void
    {
        self::assertSame('application/pdf', $this->generator->mimeTypeForExtension('pdf'));
        self::assertSame('application/pdf', $this->generator->mimeTypeForExtension('.pdf'));
        self::assertSame('application/json', $this->generator->mimeTypeForExtension('json'));
        self::assertSame('application/msword', $this->generator->mimeTypeForExtension('doc'));
        self::assertSame('application/msword', $this->generator->mimeTypeForExtension('.dot'));
        self::assertSame('application/octet-stream', $this->generator->mimeTypeForExtension('bin'));
        self::assertSame('application/pgp-signature', $this->generator->mimeTypeForExtension('sig'));
    }

    public function testMimeTypeForExtensionUnknownThrows(): void
    {
        $this->expectException(\DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException::class);
        $this->generator->mimeTypeForExtension('nonexistent_ext_xyz123');
    }
}

