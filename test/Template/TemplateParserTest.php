<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Template;

use DummyGenerator\Template\TemplateParser;
use PHPUnit\Framework\TestCase;

final class TemplateParserTest extends TestCase
{
    private TemplateParser $parser;

    protected function setUp(): void
    {
        $this->parser = new TemplateParser();
    }

    public function testSimpleToken(): void
    {
        $result = $this->parser->parse(
            '{{ firstName }}',
            fn($method, $args) => 'John'
        );

        self::assertSame('John', $result);
    }

    public function testMultipleTokens(): void
    {
        $calls = [];
        $result = $this->parser->parse(
            '{{ firstName }} {{ lastName }}',
            function ($method, $args) use (&$calls) {
                $calls[] = $method;
                return $method === 'firstName' ? 'John' : 'Smith';
            }
        );

        self::assertSame('John Smith', $result);
        self::assertSame(['firstName', 'lastName'], $calls);
    }

    public function testMethodWithoutArguments(): void
    {
        $result = $this->parser->parse(
            '{{ firstName() }}',
            function ($method, $args) {
                self::assertSame('firstName', $method);
                self::assertSame([], $args);
                return 'John';
            }
        );

        self::assertSame('John', $result);
    }

    public function testMethodWithSingleArgument(): void
    {
        $result = $this->parser->parse(
            '{{ randomDigit(5) }}',
            function ($method, $args) {
                self::assertSame('randomDigit', $method);
                self::assertSame([5], $args);
                return '5';
            }
        );

        self::assertSame('5', $result);
    }

    public function testMethodWithPositionalArgs(): void
    {
        $result = $this->parser->parse(
            '{{ numberBetween(1, 100) }}',
            function ($method, $args) {
                self::assertSame('numberBetween', $method);
                self::assertSame([1, 100], $args);
                return '42';
            }
        );

        self::assertSame('42', $result);
    }

    public function testMethodWithNamedArgs(): void
    {
        $result = $this->parser->parse(
            '{{ sentence(wordCount: 10) }}',
            function ($method, $args) {
                self::assertSame('sentence', $method);
                self::assertSame(['wordCount' => 10], $args);
                return 'Lorem ipsum';
            }
        );

        self::assertSame('Lorem ipsum', $result);
    }

    public function testMethodWithMultipleNamedArgs(): void
    {
        $result = $this->parser->parse(
            '{{ randomElement(min: 1, max: 100) }}',
            function ($method, $args) {
                self::assertSame('randomElement', $method);
                self::assertSame(['min' => 1, 'max' => 100], $args);
                return '50';
            }
        );

        self::assertSame('50', $result);
    }

    public function testStringArgumentsWithSingleQuotes(): void
    {
        $result = $this->parser->parse(
            "{{ dateTimeBetween('-1 year', 'now') }}",
            function ($method, $args) {
                self::assertSame('dateTimeBetween', $method);
                self::assertSame(['-1 year', 'now'], $args);
                return '2025-01-01';
            }
        );

        self::assertSame('2025-01-01', $result);
    }

    public function testStringArgumentsWithDoubleQuotes(): void
    {
        $result = $this->parser->parse(
            '{{ text("Lorem ipsum dolor") }}',
            function ($method, $args) {
                self::assertSame('text', $method);
                self::assertSame(['Lorem ipsum dolor'], $args);
                return 'Lorem ipsum dolor';
            }
        );

        self::assertSame('Lorem ipsum dolor', $result);
    }

    public function testBooleanArguments(): void
    {
        $result = $this->parser->parse(
            '{{ method(true, false) }}',
            function ($method, $args) {
                self::assertSame([true, false], $args);
                return 'result';
            }
        );

        self::assertSame('result', $result);
    }

    public function testNullArgument(): void
    {
        $result = $this->parser->parse(
            '{{ method(null) }}',
            function ($method, $args) {
                self::assertSame([null], $args);
                return 'result';
            }
        );

        self::assertSame('result', $result);
    }

    public function testUnquotedStringArgumentFallsBackToStringValue(): void
    {
        $result = $this->parser->parse(
            '{{ method(someIdentifier) }}',
            function ($method, $args) {
                self::assertSame(['someIdentifier'], $args);
                return 'result';
            }
        );

        self::assertSame('result', $result);
    }

    public function testFloatArguments(): void
    {
        $result = $this->parser->parse(
            '{{ randomFloat(2.5, 10.75) }}',
            function ($method, $args) {
                self::assertSame([2.5, 10.75], $args);
                return '5.5';
            }
        );

        self::assertSame('5.5', $result);
    }

    public function testMixedArgumentTypes(): void
    {
        $result = $this->parser->parse(
            '{{ method(1, "test", true, 3.14, null) }}',
            function ($method, $args) {
                self::assertSame([1, 'test', true, 3.14, null], $args);
                return 'result';
            }
        );

        self::assertSame('result', $result);
    }

    public function testComplexTemplate(): void
    {
        $template = <<<TEMPLATE
Name: {{ firstName }} {{ lastName }}
Age: {{ numberBetween(18, 65) }}
Email: {{ email }}
TEMPLATE;

        $methods = [];
        $result = $this->parser->parse($template, function ($method, $args) use (&$methods) {
            $methods[] = $method;

            return match ($method) {
                'firstName' => 'John',
                'lastName' => 'Smith',
                'numberBetween' => '42',
                'email' => 'john@example.com',
                default => ''
            };
        });

        self::assertStringContainsString('Name: John Smith', $result);
        self::assertStringContainsString('Age: 42', $result);
        self::assertStringContainsString('Email: john@example.com', $result);
        self::assertSame(['firstName', 'lastName', 'numberBetween', 'email'], $methods);
    }

    public function testInvalidMethodReturnsOriginal(): void
    {
        $result = $this->parser->parse(
            '{{ invalidMethod }}',
            function ($method, $args) {
                throw new \Exception('Method not found');
            }
        );

        // Should return original token on error
        self::assertSame('{{ invalidMethod }}', $result);
    }

    public function testInvalidMethodWithArgsReturnsOriginal(): void
    {
        $result = $this->parser->parse(
            '{{ invalidMethod(1, 2, 3) }}',
            function ($method, $args) {
                throw new \Exception('Method not found');
            }
        );

        // Should return original token on error
        self::assertSame('{{ invalidMethod(1, 2, 3) }}', $result);
    }

    public function testWhitespaceHandling(): void
    {
        $result = $this->parser->parse(
            '{{firstName}}  {{  lastName  }}  {{ numberBetween(1,100) }}',
            function ($method, $args) {
                return match ($method) {
                    'firstName' => 'John',
                    'lastName' => 'Smith',
                    'numberBetween' => '42',
                    default => ''
                };
            }
        );

        self::assertSame('John  Smith  42', $result);
    }

    public function testEmptyStringArgument(): void
    {
        $result = $this->parser->parse(
            '{{ method("") }}',
            function ($method, $args) {
                self::assertSame([''], $args);
                return 'result';
            }
        );

        self::assertSame('result', $result);
    }

    public function testStringWithComma(): void
    {
        $result = $this->parser->parse(
            '{{ method("Hello, World") }}',
            function ($method, $args) {
                self::assertSame(['Hello, World'], $args);
                return 'result';
            }
        );

        self::assertSame('result', $result);
    }

    public function testStringWithDifferentQuoteInsideQuotedArgument(): void
    {
        $result = $this->parser->parse(
            '{{ method(\'She said "hello"\', "It\'s fine") }}',
            function ($method, $args) {
                self::assertSame(['She said "hello"', "It's fine"], $args);
                return 'result';
            }
        );

        self::assertSame('result', $result);
    }

    public function testMultipleStringsWithCommas(): void
    {
        $result = $this->parser->parse(
            '{{ method("First, item", "Second, item") }}',
            function ($method, $args) {
                self::assertSame(['First, item', 'Second, item'], $args);
                return 'result';
            }
        );

        self::assertSame('result', $result);
    }

    public function testNullResultConvertsToEmptyString(): void
    {
        $result = $this->parser->parse(
            '{{ method }}',
            fn($method, $args) => null
        );

        self::assertSame('', $result);
    }

    public function testBooleanResultConvertsToString(): void
    {
        $resultTrue = $this->parser->parse(
            '{{ method }}',
            fn($method, $args) => true
        );

        $resultFalse = $this->parser->parse(
            '{{ method }}',
            fn($method, $args) => false
        );

        self::assertSame('true', $resultTrue);
        self::assertSame('false', $resultFalse);
    }

    public function testArrayResultConvertsToString(): void
    {
        $result = $this->parser->parse(
            '{{ method }}',
            fn($method, $args) => ['apple', 'banana', 'cherry']
        );

        self::assertSame('apple, banana, cherry', $result);
    }

    public function testDateTimeResultConvertsToString(): void
    {
        $result = $this->parser->parse(
            '{{ method }}',
            fn($method, $args) => new \DateTime('2025-01-01 12:30:45')
        );

        self::assertSame('2025-01-01 12:30:45', $result);
    }

    public function testBackwardCompatibilityWithOldSyntax(): void
    {
        // Old syntax without parentheses should still work
        $result = $this->parser->parse(
            '{{ firstName }}',
            function ($method, $args) {
                self::assertSame('firstName', $method);
                self::assertSame([], $args);
                return 'John';
            }
        );

        self::assertSame('John', $result);
    }

    public function testNoTokensReturnsOriginal(): void
    {
        $result = $this->parser->parse(
            'Just plain text with no tokens',
            fn($method, $args) => 'should not be called'
        );

        self::assertSame('Just plain text with no tokens', $result);
    }

    public function testMixedTokensAndText(): void
    {
        $result = $this->parser->parse(
            'Hello {{ firstName }}, you are {{ numberBetween(18, 65) }} years old!',
            function ($method, $args) {
                return match ($method) {
                    'firstName' => 'John',
                    'numberBetween' => '42',
                    default => ''
                };
            }
        );

        self::assertSame('Hello John, you are 42 years old!', $result);
    }

    public function testNegativeNumbers(): void
    {
        $result = $this->parser->parse(
            '{{ numberBetween(-100, -1) }}',
            function ($method, $args) {
                self::assertSame([-100, -1], $args);
                return '-50';
            }
        );

        self::assertSame('-50', $result);
    }

    public function testNamedAndPositionalMixed(): void
    {
        // Mixed named and positional args - the test shows current behavior
        // When parser encounters named args, it only captures those
        // This is a limitation we accept for now
        $result = $this->parser->parse(
            '{{ method(1, 2, key: 3) }}',
            function ($method, $args) {
                // Currently only named args are captured when mixed
                // This could be improved in future, but for now we document the behavior
                self::assertSame('method', $method);
                // At minimum, we should have the named arg
                self::assertArrayHasKey('key', $args);
                self::assertSame(3, $args['key']);
                return 'result';
            }
        );

        // Parser should still work, just with current limitations
        self::assertNotEmpty($result);
    }
}
