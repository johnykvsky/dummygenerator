<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Container;

use DummyGenerator\Container\DefinitionMap;
use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\Container\DefinitionMapInterface;
use DummyGenerator\Container\ExtensionRegistryInterface;
use DummyGenerator\Definitions\DefinitionInterface;
use PHPUnit\Framework\TestCase;

use function DI\factory;

final class DiContainerFactoryTest extends TestCase
{
    public function testCustomDefinitionMapImplementationIsUsed(): void
    {
        $definitions = [
            DefinitionMapInterface::class => CustomDefinitionMap::class,
        ];

        $container = DiContainerFactory::custom($definitions);

        $map = $container->get(DefinitionMapInterface::class);

        self::assertInstanceOf(CustomDefinitionMap::class, $map);
    }

    public function testCustomExtensionRegistryImplementationIsUsed(): void
    {
        $definitions = [
            ExtensionRegistryInterface::class => CustomExtensionRegistry::class,
            'custom.extension' => new class implements DefinitionInterface {},
        ];

        $container = DiContainerFactory::custom($definitions);

        $registry = $container->get(ExtensionRegistryInterface::class);

        self::assertInstanceOf(CustomExtensionRegistry::class, $registry);
        self::assertContains('custom.extension', $registry->registry());
    }

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

        /** @var array<string, mixed> $normalized */
        $normalized = $method->invoke(null, $definitions);

        return $normalized;
    }
}

final class CustomDefinitionMap implements DefinitionMapInterface
{
    /** @var array<string, mixed> */
    private array $definitions;

    /** @param array<string, mixed> $definitions */
    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return $this->definitions;
    }

    public function with(string $id, mixed $definition): DefinitionMap
    {
        $clone = clone $this;
        $clone->definitions[$id] = $definition;

        return new DefinitionMap($clone->definitions);
    }

    public function set(string $id, mixed $definition): void
    {
        $this->definitions[$id] = $definition;
    }

    /** @param array<string, mixed> $definitions */
    public function withMany(array $definitions): DefinitionMap
    {
        $clone = clone $this;

        foreach ($definitions as $id => $definition) {
            $clone->definitions[$id] = $definition;
        }

        return new DefinitionMap($clone->definitions);
    }

    public function without(string $id): DefinitionMap
    {
        $clone = clone $this;
        unset($clone->definitions[$id]);

        return new DefinitionMap($clone->definitions);
    }
}

final class CustomExtensionRegistry implements ExtensionRegistryInterface
{
    /** @param string[] $ids */
    public function __construct(
        private array $ids,
    ) {
    }

    public function register(string $id): void
    {
        $this->ids[] = $id;
    }

    /** @return string[] */
    public function registry(): array
    {
        return $this->ids;
    }
}
