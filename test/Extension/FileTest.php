<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
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

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(FileExtensionInterface::class, File::class);
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

}
