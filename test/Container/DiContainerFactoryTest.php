<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Container;

use DummyGenerator\Container\DiContainerFactory;
use PHPUnit\Framework\TestCase;

use function DI\factory;

final class DiContainerFactoryTest extends TestCase
{
    public function testNormalizeDefinitionsKeepsDefinitionHelperInstance(): void
    {
        $helper = factory(static fn () => 'value');

        $normalized = $this->invokeNormalizeDefinitions([
            'service' => $helper,
        ]);

        self::assertArrayHasKey('service', $normalized);
        self::assertSame($helper, $normalized['service']);
    }

    public function testNormalizeDefinitionsReturnsOriginalDefinitionWhenNoConversionApplies(): void
    {
        $rawDefinition = 'not_a_real_class_name';

        $normalized = $this->invokeNormalizeDefinitions([
            'raw' => $rawDefinition,
        ]);

        self::assertArrayHasKey('raw', $normalized);
        self::assertSame($rawDefinition, $normalized['raw']);
    }

    /**
     * @param array<string, mixed> $definitions
     * @return array<string, mixed>
     */
    private function invokeNormalizeDefinitions(array $definitions): array
    {
        $method = new \ReflectionMethod(DiContainerFactory::class, 'normalizeDefinitions');
        $method->setAccessible(true);

        /** @var array<string, mixed> $normalized */
        $normalized = $method->invoke(null, $definitions);

        return $normalized;
    }
}
