<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Definitions\Extension\LanguageExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Language;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(LanguageExtensionInterface::class, Language::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testLanguageCode(): void
    {
        self::assertEquals(2, strlen($this->generator->languageCode()));
    }

    public function testLocale(): void
    {
        self::assertTrue(str_contains($this->generator->locale(), '_'));
    }
}