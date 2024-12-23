<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Text;
use DummyGenerator\Core\Transliterator\Transliterator;
use DummyGenerator\Definitions\Extension\TextExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Test\Fixtures\TextProvider;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(TransliteratorInterface::class, Transliterator::class);
        $container->add(ReplacerInterface::class, Replacer::class);
        $container->add(TextExtensionInterface::class, Text::class);
        $this->generator = new DummyGenerator($container);
    }
    public function testRealText(): void
    {
        $realText = $this->generator->realText(min: 5, max: 50, indexSize: 3);

        // @phpstan-ignore-next-line
        $length = $this->generator->ext(ReplacerInterface::class)->strlen($realText);

        self::assertTrue($length >= 5 && $length <= 50);
    }
}
