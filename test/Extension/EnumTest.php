<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Enum;
use DummyGenerator\Definitions\Extension\EnumExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Test\Fixtures\BarProvider;
use DummyGenerator\Test\Fixtures\SuitBackedIntEnum;
use DummyGenerator\Test\Fixtures\SuitBackedStringEnum;
use DummyGenerator\Test\Fixtures\SuitEnum;
use PHPUnit\Framework\TestCase;
use UnitEnum;

class EnumTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(EnumExtensionInterface::class, Enum::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testStringValue(): void
    {
        self::assertIsString($this->generator->enumValue(SuitBackedStringEnum::class));
    }

    public function testIntValue(): void
    {
        self::assertIsInt($this->generator->enumValue(SuitBackedIntEnum::class));
    }

    public function testValueForInvalidClass(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Invalid PHP Enum');
        $this->generator->enumValue('none_enum_string');
    }

    public function testValueForNonBacked(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Argument should be backed PHP Enum');
        $this->generator->enumValue(SuitEnum::class);
    }

    public function testElement(): void
    {
        self::assertInstanceOf(UnitEnum::class, $this->generator->enumCase(SuitEnum::class));
    }

    public function testBackedElement(): void
    {
        self::assertInstanceOf(UnitEnum::class, $this->generator->enumCase(SuitBackedStringEnum::class));
    }

    public function testElementForInvalidClass(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Invalid PHP Enum');
        $this->generator->enumCase('none_enum_string');
    }

    public function testElementForNonEnumClass(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Invalid PHP Enum');
        $this->generator->enumCase(BarProvider::class);
    }

    /**
     * Test that enumValue returns consistent types for string-backed enum.
     *
     * @group enum
     * @group edge-case
     */
    public function testEnumValueReturnsConsistentTypeForStringBacked(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $value = $this->generator->enumValue(SuitBackedStringEnum::class);
            self::assertIsString($value);
        }
    }

    /**
     * Test that enumValue returns consistent types for int-backed enum.
     *
     * @group enum
     * @group edge-case
     */
    public function testEnumValueReturnsConsistentTypeForIntBacked(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $value = $this->generator->enumValue(SuitBackedIntEnum::class);
            self::assertIsInt($value);
        }
    }

    /**
     * Test that enumCase returns valid enum instances.
     *
     * @group enum
     */
    public function testEnumCaseReturnsValidEnumInstance(): void
    {
        $case = $this->generator->enumCase(SuitEnum::class);

        self::assertInstanceOf(SuitEnum::class, $case);
        self::assertContains($case, SuitEnum::cases());
    }

    /**
     * Test that enumCase for backed enum returns valid instance.
     *
     * @group enum
     */
    public function testEnumCaseForBackedEnumReturnsValidInstance(): void
    {
        $case = $this->generator->enumCase(SuitBackedStringEnum::class);

        self::assertInstanceOf(SuitBackedStringEnum::class, $case);
        self::assertContains($case, SuitBackedStringEnum::cases());
    }

    /**
     * Test that multiple enum value calls produce variety.
     *
     * @group enum
     */
    public function testEnumValueProducesVariety(): void
    {
        $values = [];
        for ($i = 0; $i < 20; $i++) {
            $values[] = $this->generator->enumValue(SuitBackedStringEnum::class);
        }

        $unique = array_unique($values);
        self::assertGreaterThan(1, count($unique), 'Should generate different enum values');
    }

    /**
     * Test that multiple enum case calls produce variety.
     *
     * @group enum
     */
    public function testEnumCaseProducesVariety(): void
    {
        $cases = [];
        for ($i = 0; $i < 20; $i++) {
            $cases[] = $this->generator->enumCase(SuitEnum::class)->name;
        }

        $unique = array_unique($cases);
        self::assertGreaterThan(1, count($unique), 'Should generate different enum cases');
    }
}