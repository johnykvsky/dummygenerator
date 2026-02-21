<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\AnyDateTime;
use DummyGenerator\Core\Biased;
use DummyGenerator\Core\Coordinates;
use DummyGenerator\Core\DateTime;
use DummyGenerator\Core\Enum;
use DummyGenerator\Core\Lorem;
use DummyGenerator\Core\Number;
use DummyGenerator\Core\Strings;
use DummyGenerator\Definitions\Extension\AnyDateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\BiasedExtensionInterface;
use DummyGenerator\Definitions\Extension\CoordinatesExtensionInterface;
use DummyGenerator\Definitions\Extension\DateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\EnumExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Extension\Exception\ExtensionLogicException;
use DummyGenerator\Definitions\Extension\Exception\ExtensionOverflowException;
use DummyGenerator\Definitions\Extension\Exception\ExtensionRuntimeException;
use DummyGenerator\Definitions\Extension\LoremExtensionInterface;
use DummyGenerator\Definitions\Extension\NumberExtensionInterface;
use DummyGenerator\Definitions\Extension\StringsExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(StringsExtensionInterface::class, Strings::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(DateTimeExtensionInterface::class, DateTime::class);
        $container->set(AnyDateTimeExtensionInterface::class, AnyDateTime::class);
        $container->set(CoordinatesExtensionInterface::class, Coordinates::class);
        $container->set(EnumExtensionInterface::class, Enum::class);
        $container->set(BiasedExtensionInterface::class, Biased::class);

        $this->generator = new DummyGenerator($container);
    }

    // ExtensionArgumentException Tests

    public function testStringMinBelowLimitThrowsArgumentException(): void
    {
        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('$min should be at least 1');

        $this->generator->string(min: 0, max: 10);
    }

    public function testStringMinHigherThanMaxThrowsArgumentException(): void
    {
        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('$min cannot be higher than $max');

        $this->generator->string(min: 10, max: 5);
    }

    public function testLoremSentenceWordCountBelowMinThrowsArgumentException(): void
    {
        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('$wordCount should be at least 1');

        $this->generator->sentence(wordCount: 0);
    }

    public function testLoremParagraphSentenceCountBelowMinThrowsArgumentException(): void
    {
        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('$sentenceCount should be at least 1');

        $this->generator->paragraph(sentenceCount: 0);
    }

    public function testLoremTextMaxCharactersBelowMinThrowsArgumentException(): void
    {
        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('$maxCharacters can only generate text of at least 5 characters');

        $this->generator->text(maxCharacters: 2);
    }

    public function testDateTimeBetweenWithMaxBeforeMinThrowsArgumentException(): void
    {
        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('"$from" must be anterior to "$until"');

        $min = new \DateTimeImmutable('2025-01-01');
        $max = new \DateTimeImmutable('2024-01-01');

        $this->generator->dateTimeBetween($min, $max);
    }

    public function testAnyDateBetweenWithUntilBeforeFromThrowsArgumentException(): void
    {
        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('"from" must be anterior to "until"');

        $from = new \DateTimeImmutable('2025-01-01');
        $until = new \DateTimeImmutable('2024-01-01');

        $this->generator->anyDateBetween(from: $from, until: $until);
    }

    // ExtensionLogicException Tests

    public function testCoordinatesLatitudeMinBelowAllowedThrowsLogicException(): void
    {
        $this->expectException(ExtensionLogicException::class);
        $this->expectExceptionMessage('Latitude cannot be less that -90.0');

        $this->generator->latitude(min: -100.0, max: -100.0);
    }

    public function testCoordinatesLatitudeMaxAboveAllowedThrowsLogicException(): void
    {
        $this->expectException(ExtensionLogicException::class);
        $this->expectExceptionMessage('Latitude cannot be greater that 90.0');

        $this->generator->latitude(min: 100.0, max: 100.0);
    }

    public function testCoordinatesLongitudeMinBelowAllowedThrowsLogicException(): void
    {
        $this->expectException(ExtensionLogicException::class);
        $this->expectExceptionMessage('Longitude cannot be less that -180.0');

        $this->generator->longitude(min: -200.0, max: -200.0);
    }

    public function testCoordinatesLongitudeMaxAboveAllowedThrowsLogicException(): void
    {
        $this->expectException(ExtensionLogicException::class);
        $this->expectExceptionMessage('Longitude cannot be greater that 180.0');

        $this->generator->longitude(min: 200.0, max: 200.0);
    }

    // Exception Message Validation

    public function testExceptionMessagesAreDescriptive(): void
    {
        try {
            $this->generator->string(min: 0, max: 10);
            $this->fail('Expected ExtensionArgumentException was not thrown');
        } catch (ExtensionArgumentException $e) {
            // Exception message should describe the problem
            self::assertStringContainsString('$min', $e->getMessage());
            self::assertStringContainsString('at least 1', $e->getMessage());
        }
    }

    public function testExceptionMessageIncludesParameterName(): void
    {
        try {
            $this->generator->sentence(wordCount: 0);
            $this->fail('Expected ExtensionArgumentException was not thrown');
        } catch (ExtensionArgumentException $e) {
            // Exception message should include parameter name
            self::assertStringContainsString('wordCount', $e->getMessage());
        }
    }

    // Exception Inheritance

    public function testExtensionArgumentExceptionExtendsInvalidArgumentException(): void
    {
        $exception = new ExtensionArgumentException('test');

        self::assertInstanceOf(\InvalidArgumentException::class, $exception);
    }

    public function testExtensionLogicExceptionExtendsLogicException(): void
    {
        $exception = new ExtensionLogicException('test');

        self::assertInstanceOf(\LogicException::class, $exception);
    }

    public function testExtensionRuntimeExceptionExtendsRuntimeException(): void
    {
        $exception = new ExtensionRuntimeException('test');

        self::assertInstanceOf(\RuntimeException::class, $exception);
    }

    public function testExtensionOverflowExceptionExtendsOverflowException(): void
    {
        $exception = new ExtensionOverflowException('test');

        self::assertInstanceOf(\OverflowException::class, $exception);
    }

    // Exception with Custom Messages

    public function testExceptionCanBeCreatedWithCustomMessage(): void
    {
        $customMessage = 'Custom error message for testing';
        $exception = new ExtensionArgumentException($customMessage);

        self::assertEquals($customMessage, $exception->getMessage());
    }

    public function testExceptionCanBeCreatedWithCode(): void
    {
        $exception = new ExtensionArgumentException('test', 123);

        self::assertEquals(123, $exception->getCode());
    }

    public function testExceptionCanBeCreatedWithPreviousException(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new ExtensionArgumentException('test', 0, $previous);

        self::assertSame($previous, $exception->getPrevious());
    }

    // Multiple Parameter Validation

    public function testMultipleParameterValidationInSingleMethod(): void
    {
        // Test that string() validates both min and max correctly

        // First, test min < 1
        try {
            $this->generator->string(min: 0, max: 10);
            $this->fail('Expected exception for min < 1');
        } catch (ExtensionArgumentException $e) {
            self::assertStringContainsString('$min should be at least 1', $e->getMessage());
        }

        // Then, test min > max
        try {
            $this->generator->string(min: 10, max: 5);
            $this->fail('Expected exception for min > max');
        } catch (ExtensionArgumentException $e) {
            self::assertStringContainsString('$min cannot be higher than $max', $e->getMessage());
        }
    }

    // Exception Context Preservation

    public function testExceptionPreservesStackTrace(): void
    {
        try {
            $this->generator->sentence(wordCount: 0);
        } catch (ExtensionArgumentException $e) {
            $trace = $e->getTrace();

            // Stack trace should not be empty
            self::assertNotEmpty($trace);

            // Should contain call to Lorem extension
            $hasLoremInTrace = false;
            foreach ($trace as $frame) {
                if (isset($frame['class']) && str_contains($frame['class'], 'Lorem')) {
                    $hasLoremInTrace = true;
                    break;
                }
            }

            self::assertTrue($hasLoremInTrace, 'Stack trace should contain Lorem class');
        }
    }

    // Boundary Value Testing

    public function testExceptionThrownAtExactBoundary(): void
    {
        // Test that exception is thrown at exact boundary values

        // Min = 0 should throw (boundary is >= 1)
        $this->expectException(ExtensionArgumentException::class);
        $this->generator->string(min: 0, max: 5);
    }

    public function testNoExceptionJustAboveBoundary(): void
    {
        // Min = 1 should NOT throw (boundary is >= 1)
        $result = $this->generator->string(min: 1, max: 5);

        self::assertIsString($result);
        self::assertGreaterThanOrEqual(1, strlen($result));
        self::assertLessThanOrEqual(5, strlen($result));
    }

    // Exception Type Differentiation

    public function testDifferentExceptionTypesForDifferentErrors(): void
    {
        // ArgumentException for invalid arguments
        try {
            $this->generator->string(min: 0, max: 5);
            $this->fail('Expected ExtensionArgumentException');
        } catch (ExtensionArgumentException $e) {
            self::assertInstanceOf(ExtensionArgumentException::class, $e);
        }

        // LogicException for logic errors
        try {
            $this->generator->latitude(min: -100.0, max: -100.0);
            $this->fail('Expected ExtensionLogicException');
        } catch (ExtensionLogicException $e) {
            self::assertInstanceOf(ExtensionLogicException::class, $e);
        }
    }
}
