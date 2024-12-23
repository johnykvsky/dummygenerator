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

    public function testRealTextConstructor(): void
    {
        $text = <<<'EOT'
Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 

Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 

Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 

Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
EOT;

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(TransliteratorInterface::class, Transliterator::class);
        $container->add(ReplacerInterface::class, Replacer::class);
        $container->add(TextExtensionInterface::class, new Text($text));
        $this->generator = new DummyGenerator($container);

        $realText = $this->generator->realText(min: 5, max: 50, indexSize: 3);

        // @phpstan-ignore-next-line
        $length = $this->generator->ext(ReplacerInterface::class)->strlen($realText);

        self::assertTrue($length >= 5 && $length <= 50);
        self::assertTrue(str_contains($text, rtrim($realText, '.')));
    }
}
