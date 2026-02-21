<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
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

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(LanguageExtensionInterface::class, Language::class);
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