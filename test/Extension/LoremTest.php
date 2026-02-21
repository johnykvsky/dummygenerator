<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Lorem;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
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

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
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

    public function testSentenceBelowMin(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('$wordCount should be at least 1');
        $this->generator->sentence(wordCount: 0);
    }

    public function testParagraph(): void
    {
        self::assertNotEmpty($this->generator->paragraph(sentenceCount: 4, variableSentenceCount: false));
    }

    public function testParagraphBelowMin(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('$sentenceCount should be at least 1');
        $this->generator->paragraph(sentenceCount: 0);
    }

    public function testParagraphs(): void
    {
        self::assertCount(5, $this->generator->paragraphs(paragraphCount: 5));
    }

    public function testText(): void
    {
        self::assertTrue(strlen($this->generator->text(maxCharacters: 80)) < 80);
    }

    public function testTextBelowMax(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('$maxCharacters can only generate text of at least 5 characters');
        $this->generator->text(maxCharacters: 2);
    }

    public function testTextWord(): void
    {
        self::assertTrue(strlen($this->generator->text(maxCharacters: 20)) < 20);
    }

    public function testTextParagraph(): void
    {
        self::assertTrue(strlen($this->generator->text(maxCharacters: 120)) < 120);
    }

    public function testTextRetriesWhenFirstPassProducesEmptyText(): void
    {
        $calls = 0;
        $randomizer = $this->createMock(RandomizerInterface::class);
        $randomizer->expects(self::exactly(4))
            ->method('randomElement')
            ->willReturnCallback(static function () use (&$calls): string {
                $calls++;

                return match ($calls) {
                    1 => 'xxxxx', // first outer pass => popped => empty text
                    2, 3, 4 => 'a', // second outer pass => produces final text
                };
            });

        $replacer = $this->createMock(ReplacerInterface::class);
        $replacer->expects(self::atLeast(1))
            ->method('strlen')
            ->willReturnCallback(static fn (string $value): int => strlen($value));

        $lorem = new Lorem($randomizer, $replacer);

        $result = $lorem->text(maxCharacters: 5);

        self::assertSame(4, $calls, '4 word generations prove a retry pass was executed.');
        self::assertSame('a a', $result);
    }

    // Enhanced validation tests

    public function testWordIsNonEmpty(): void
    {
        $word = $this->generator->word();

        self::assertIsString($word);
        self::assertNotEmpty($word);
        self::assertGreaterThan(0, strlen($word));
    }

    public function testWordsReturnsCorrectCount(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $words = $this->generator->words(wordCount: $i);
            self::assertCount($i, $words);
        }
    }

    public function testWordsReturnsArrayOfStrings(): void
    {
        $words = $this->generator->words(wordCount: 5);

        self::assertIsArray($words);
        foreach ($words as $word) {
            self::assertIsString($word);
            self::assertNotEmpty($word);
        }
    }

    public function testSentenceEndsWithPeriod(): void
    {
        $sentence = $this->generator->sentence(wordCount: 5, variableWordCount: false);

        self::assertStringEndsWith('.', $sentence);
    }

    public function testSentenceStartsWithCapitalLetter(): void
    {
        $sentence = $this->generator->sentence(wordCount: 5, variableWordCount: false);

        self::assertMatchesRegularExpression('/^[A-Z]/', $sentence);
    }

    public function testSentenceWithVariableWordCount(): void
    {
        $lengths = [];

        for ($i = 0; $i < 20; $i++) {
            $sentence = $this->generator->sentence(wordCount: 6, variableWordCount: true);
            $words = str_word_count($sentence);
            $lengths[] = $words;
        }

        // Should have some variety in lengths
        $uniqueLengths = array_unique($lengths);
        self::assertGreaterThan(1, count($uniqueLengths), 'Should generate sentences with varying word counts');
    }

    public function testSentencesReturnsCorrectCount(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $sentences = $this->generator->sentences(sentenceCount: $i);
            self::assertCount($i, $sentences);
        }
    }

    public function testSentencesReturnsArrayOfStrings(): void
    {
        $sentences = $this->generator->sentences(sentenceCount: 3);

        self::assertIsArray($sentences);
        foreach ($sentences as $sentence) {
            self::assertIsString($sentence);
            self::assertNotEmpty($sentence);
            self::assertStringEndsWith('.', $sentence);
        }
    }

    public function testParagraphIsNonEmpty(): void
    {
        $paragraph = $this->generator->paragraph(sentenceCount: 3, variableSentenceCount: false);

        self::assertIsString($paragraph);
        self::assertNotEmpty($paragraph);
    }

    public function testParagraphContainsMultipleSentences(): void
    {
        $paragraph = $this->generator->paragraph(sentenceCount: 5, variableSentenceCount: false);

        // Should have multiple periods (one for each sentence)
        $periodCount = substr_count($paragraph, '.');
        self::assertGreaterThanOrEqual(5, $periodCount);
    }

    public function testParagraphWithVariableSentenceCount(): void
    {
        $periodCounts = [];

        for ($i = 0; $i < 20; $i++) {
            $paragraph = $this->generator->paragraph(sentenceCount: 4, variableSentenceCount: true);
            $periodCounts[] = substr_count($paragraph, '.');
        }

        // Should have some variety in sentence counts
        $uniqueCounts = array_unique($periodCounts);
        self::assertGreaterThan(1, count($uniqueCounts), 'Should generate paragraphs with varying sentence counts');
    }

    public function testParagraphsReturnsCorrectCount(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $paragraphs = $this->generator->paragraphs(paragraphCount: $i);
            self::assertCount($i, $paragraphs);
        }
    }

    public function testParagraphsReturnsArrayOfStrings(): void
    {
        $paragraphs = $this->generator->paragraphs(paragraphCount: 3);

        self::assertIsArray($paragraphs);
        foreach ($paragraphs as $paragraph) {
            self::assertIsString($paragraph);
            self::assertNotEmpty($paragraph);
        }
    }

    public function testTextRespectsMaxCharacters(): void
    {
        for ($maxChars = 50; $maxChars <= 500; $maxChars += 50) {
            $text = $this->generator->text(maxCharacters: $maxChars);
            self::assertLessThanOrEqual($maxChars, strlen($text), "Text should not exceed $maxChars characters");
        }
    }

    public function testTextIsNonEmpty(): void
    {
        $text = $this->generator->text(maxCharacters: 100);

        self::assertIsString($text);
        self::assertNotEmpty($text);
        self::assertGreaterThan(0, strlen($text));
    }

    public function testTextHasReasonableLength(): void
    {
        $text = $this->generator->text(maxCharacters: 200);

        // Should be at least somewhat substantial
        self::assertGreaterThan(10, strlen($text));
    }

    public function testWordGeneratesDifferentWords(): void
    {
        $words = [];
        for ($i = 0; $i < 30; $i++) {
            $words[] = $this->generator->word();
        }

        $uniqueWords = array_unique($words);
        self::assertGreaterThan(1, count($uniqueWords), 'Should generate different words');
    }

    public function testWordsWithZeroCountReturnsEmptyArray(): void
    {
        $words = $this->generator->words(wordCount: 0);
        self::assertIsArray($words);
        self::assertEmpty($words);
    }

    public function testSentencesWithZeroCountReturnsEmptyArray(): void
    {
        $sentences = $this->generator->sentences(sentenceCount: 0);
        self::assertIsArray($sentences);
        self::assertEmpty($sentences);
    }

    public function testParagraphsWithZeroCountReturnsEmptyArray(): void
    {
        $paragraphs = $this->generator->paragraphs(paragraphCount: 0);
        self::assertIsArray($paragraphs);
        self::assertEmpty($paragraphs);
    }
}
