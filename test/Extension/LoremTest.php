<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\Lorem;
use DummyGenerator\Definitions\Extension\LoremExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use PHPUnit\Framework\TestCase;

class LoremTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(TransliteratorInterface::class, Transliterator::class);
        $container->add(ReplacerInterface::class, Replacer::class);
        $container->add(LoremExtensionInterface::class, Lorem::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testWord(): void
    {
        self::assertNotEmpty($this->generator->word());
    }

    public function testWords(): void
    {
        self::assertCount(4, $this->generator->words(wordCount: 4));
    }

    public function testSentence(): void
    {
        self::assertCount(5, explode(' ', $this->generator->sentence(wordCount: 5, variableWordCount: false)));
    }

    public function testSentences(): void
    {
        self::assertCount(4, $this->generator->sentences(sentenceCount: 4));
    }

    public function testParagraph(): void
    {
        self::assertNotEmpty($this->generator->paragraph(sentenceCount: 4, variableSentenceCount: false));
    }

    public function testParagraphs(): void
    {
        self::assertCount(5, $this->generator->paragraphs(paragraphCount: 5));
    }

    public function testText(): void
    {
        self::assertTrue(strlen($this->generator->text(maxCharacters: 80)) < 80);
    }

    public function testTextWord(): void
    {
        self::assertTrue(strlen($this->generator->text(maxCharacters: 20)) < 20);
    }

    public function testTextParagraph(): void
    {
        self::assertTrue(strlen($this->generator->text(maxCharacters: 120)) < 120);
    }
}
