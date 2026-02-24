<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Generator;

use DummyGenerator\Core\Lorem;
use DummyGenerator\Core\Number;
use DummyGenerator\Core\Person;
use DummyGenerator\Definitions\Extension\LoremExtensionInterface;
use DummyGenerator\Definitions\Extension\NumberExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use DummyGenerator\Test\Fixtures\TestContainerFactory;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the parse() template method.
 *
 * The parse() method replaces {{ token }} placeholders with generated values.
 *
 * @group parse
 * @group template
 */
class ParseTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty(true);
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);

        $this->generator = new DummyGenerator($container);
    }

    /**
     * Test that parse() handles empty strings.
     *
     * @group parse
     * @group edge-case
     */
    public function testParseWithEmptyString(): void
    {
        $result = $this->generator->parse('');
        self::assertEquals('', $result);
    }

    /**
     * Test that parse() handles strings without tokens.
     *
     * @group parse
     */
    public function testParseWithNoTokens(): void
    {
        $text = 'This is plain text without tokens';
        $result = $this->generator->parse($text);
        self::assertEquals($text, $result);
    }

    /**
     * Test that parse() handles single token.
     *
     * @group parse
     */
    public function testParseWithSingleToken(): void
    {
        $result = $this->generator->parse('{{ firstName }}');
        self::assertNotEmpty($result);
        self::assertStringNotContainsString('{{', $result);
        self::assertStringNotContainsString('}}', $result);
    }

    /**
     * Test that parse() handles multiple tokens.
     *
     * @group parse
     */
    public function testParseWithMultipleTokens(): void
    {
        $template = '{{ firstName }} {{ lastName }}';
        $result = $this->generator->parse($template);

        self::assertStringNotContainsString('{{', $result);
        self::assertStringNotContainsString('}}', $result);
        self::assertStringContainsString(' ', $result); // Space between names
    }

    /**
     * Test that parse() does not support tokens with method arguments.
     *
     * The parse() method only supports simple property-like tokens,
     * not method calls with arguments.
     *
     * @group parse
     * @group limitation
     */
    public function testParseDoesNotSupportTokenArguments(): void
    {
        $template = '{{ numberBetween(1, 100) }}';
        $result = $this->generator->parse($template);

        // Enhanced parser (v2.2+) now supports method calls with arguments
        self::assertNotEquals($template, $result); // Should be replaced
        self::assertIsNumeric($result); // Should be a number
        $number = (int) $result;
        self::assertGreaterThanOrEqual(1, $number);
        self::assertLessThanOrEqual(100, $number);
    }

    /**
     * Test that parse() handles nested braces in literal text.
     *
     * @group parse
     * @group edge-case
     */
    public function testParseWithLiteralBraces(): void
    {
        $template = 'Literal { braces } and {{ firstName }}';
        $result = $this->generator->parse($template);

        self::assertStringContainsString('Literal { braces }', $result);
        self::assertStringNotContainsString('{{', $result);
        self::assertStringNotContainsString('}}', $result);
    }

    /**
     * Test that parse() handles tokens at the beginning of string.
     *
     * @group parse
     */
    public function testParseWithTokenAtBeginning(): void
    {
        $template = '{{ firstName }} is a developer';
        $result = $this->generator->parse($template);

        self::assertStringNotContainsString('{{', $result);
        self::assertStringEndsWith(' is a developer', $result);
    }

    /**
     * Test that parse() handles tokens at the end of string.
     *
     * @group parse
     */
    public function testParseWithTokenAtEnd(): void
    {
        $template = 'Hello {{ firstName }}';
        $result = $this->generator->parse($template);

        self::assertStringStartsWith('Hello ', $result);
        self::assertStringNotContainsString('{{', $result);
    }

    /**
     * Test that parse() handles consecutive tokens without space.
     *
     * @group parse
     * @group edge-case
     */
    public function testParseWithConsecutiveTokens(): void
    {
        $template = '{{ firstName }}{{ lastName }}';
        $result = $this->generator->parse($template);

        self::assertStringNotContainsString('{{', $result);
        self::assertStringNotContainsString('}}', $result);
        self::assertNotEmpty($result);
    }

    /**
     * Test that parse() handles malformed tokens gracefully.
     *
     * @group parse
     * @group edge-case
     */
    public function testParseWithMalformedTokens(): void
    {
        // Missing closing brace
        $template = '{{ firstName } text';
        $result = $this->generator->parse($template);

        // Should leave malformed tokens as-is or handle gracefully
        self::assertIsString($result);
    }

    /**
     * Test that parse() requires single space or no space around tokens.
     *
     * The regex pattern allows `{{ token }}` or `{{token}}` but not `{{  token  }}`.
     *
     * @group parse
     * @group limitation
     */
    public function testParseRequiresCorrectWhitespace(): void
    {
        // This works - single space is allowed
        $template1 = '{{ firstName }}';
        $result1 = $this->generator->parse($template1);
        self::assertStringNotContainsString('{{', $result1);

        // This works - no space
        $template2 = '{{firstName}}';
        $result2 = $this->generator->parse($template2);
        self::assertStringNotContainsString('{{', $result2);

        // Enhanced parser (v2.2+) now handles extra whitespace
        $template3 = '{{  firstName  }}';
        $result3 = $this->generator->parse($template3);
        self::assertNotEquals($template3, $result3); // Now works!
        self::assertStringNotContainsString('{{', $result3); // Token replaced
    }

    /**
     * Test that parse() handles very long templates.
     *
     * @group parse
     * @group stress
     */
    public function testParseWithVeryLongTemplate(): void
    {
        $tokens = array_fill(0, 100, '{{ word }}');
        $template = implode(' ', $tokens);

        $result = $this->generator->parse($template);

        self::assertStringNotContainsString('{{', $result);
        // Should have approximately 100 words
        $wordCount = str_word_count($result);
        self::assertGreaterThan(90, $wordCount);
    }

    /**
     * Test that parse() handles newlines and multiline templates.
     *
     * Note: Method calls with arguments are not supported.
     *
     * @group parse
     */
    public function testParseWithMultilineTemplate(): void
    {
        $template = <<<'EOT'
Name: {{ firstName }} {{ lastName }}
City: {{ word }}
EOT;

        $result = $this->generator->parse($template);

        self::assertStringContainsString('Name:', $result);
        self::assertStringContainsString('City:', $result);
        self::assertStringNotContainsString('{{', $result);
    }

    /**
     * Test that parse() preserves special characters.
     *
     * @group parse
     */
    public function testParsePreservesSpecialCharacters(): void
    {
        $template = 'Email: test@example.com, Name: {{ firstName }}';
        $result = $this->generator->parse($template);

        self::assertStringContainsString('Email: test@example.com', $result);
        self::assertStringNotContainsString('{{', $result);
    }
}
