<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

use DI\ContainerBuilder;
use DI\Definition\Helper\DefinitionHelper;
use DummyGenerator\Clock\SystemClock;
use DummyGenerator\Clock\SystemClockInterface;
use DummyGenerator\DefinitionPack\DefinitionPack;
use DummyGenerator\DefinitionPack\DefinitionPackInterface;
use DummyGenerator\GeneratorInterface;
use DummyGenerator\GeneratorProxy;
use DummyGenerator\Strategy\SimpleStrategy;
use DummyGenerator\Strategy\StrategyInterface;
use DummyGenerator\Template\TemplateParser;
use DummyGenerator\Template\TemplateParserInterface;

use function DI\autowire;
use function DI\factory;
use function DI\value;

final class DiContainerFactory
{
    private const array SYSTEM_IDS = [
        StrategyInterface::class,
        SystemClockInterface::class,
        TemplateParserInterface::class,
        GeneratorInterface::class,
        DefinitionMap::class,
        ExtensionRegistry::class,
    ];

    public static function base(
        ?DefinitionPackInterface $definitionPack = null,
        bool $withTemplateParser = false
    ): DummyContainer {
        $definitionPack ??= new DefinitionPack();

        $definitions = array_merge(
            $definitionPack->coreDefinitions(),
            $definitionPack->baseExtensions(),
        );

        return self::custom($definitions, $withTemplateParser);
    }

    public static function default(
        ?DefinitionPackInterface $definitionPack = null,
        bool $withTemplateParser = true
    ): DummyContainer {
        $definitionPack ??= new DefinitionPack();

        $definitions = array_merge(
            $definitionPack->coreDefinitions(),
            $definitionPack->baseExtensions(),
            $definitionPack->defaultExtensions(),
        );

        return self::custom($definitions, $withTemplateParser);
    }

    public static function all(
        ?DefinitionPackInterface $definitionPack = null,
        bool $withTemplateParser = true
    ): DummyContainer {
        $definitionPack ??= new DefinitionPack();

        $definitions = array_merge(
            $definitionPack->coreDefinitions(),
            $definitionPack->baseExtensions(),
            $definitionPack->calculators(),
            $definitionPack->defaultExtensions(),
            $definitionPack->complementaryExtensions(),
        );

        return self::custom($definitions, $withTemplateParser);
    }

    /**
     * Build a container from explicit definitions (used by tests and custom setups).
     *
     * @param array<string, mixed> $definitions
     */
    public static function custom(array $definitions, bool $withTemplateParser = true): DummyContainer
    {
        $definitions = self::withInfrastructure($definitions, $withTemplateParser);

        $map = new DefinitionMap($definitions);

        return self::fromDefinitionMap($map);
    }

    public static function fromDefinitionMap(DefinitionMap $map): DummyContainer
    {
        $definitions = $map->all();

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true);
        $containerBuilder->useAttributes(true);

        $normalized = self::normalizeDefinitions($definitions);
        $registry = new ExtensionRegistry(
            array_merge(
                self::resolveProcessorIds($definitions),
                self::SYSTEM_IDS,
            ),
        );

        $normalized[DefinitionMap::class] = value($map);
        $normalized[ExtensionRegistry::class] = value($registry);

        $containerBuilder->addDefinitions($normalized);

        $container = $containerBuilder->build();

        return new DummyContainer($container, $map, $registry);
    }

    /**
     * @param array<string, mixed> $definitions
     * @return array<string, mixed>
     */
    private static function normalizeDefinitions(array $definitions): array
    {
        $normalized = [];

        foreach ($definitions as $id => $definition) {
            $normalized[$id] = self::normalizeDefinition($definition);
        }

        return $normalized;
    }

    public static function normalizeDefinition(mixed $definition): mixed
    {
        if ($definition instanceof DefinitionHelper) {
            return $definition;
        }

        if (is_callable($definition)) {
            return factory($definition);
        }

        if (is_object($definition)) {
            return value($definition);
        }

        if (is_string($definition) && class_exists($definition)) {
            return autowire($definition);
        }

        return $definition;
    }

    /**
     * @param array<string, mixed> $definitions
     * @return array<string, mixed>
     */
    private static function withInfrastructure(array $definitions, bool $withTemplateParser): array
    {
        if (!array_key_exists(StrategyInterface::class, $definitions)) {
            $definitions[StrategyInterface::class] = SimpleStrategy::class;
        }

        if (!array_key_exists(SystemClockInterface::class, $definitions)) {
            $definitions[SystemClockInterface::class] = SystemClock::class;
        }

        if ($withTemplateParser && !array_key_exists(TemplateParserInterface::class, $definitions)) {
            $definitions[TemplateParserInterface::class] = TemplateParser::class;
        }

        if (!array_key_exists(GeneratorInterface::class, $definitions)) {
            $definitions[GeneratorInterface::class] = GeneratorProxy::class;
        }

        return $definitions;
    }

    /**
     * @param array<string, mixed> $definitions
     * @return array<int, string>
     */
    private static function resolveProcessorIds(array $definitions): array
    {
        $ids = [];

        foreach ($definitions as $id => $definition) {
            if (in_array($id, self::SYSTEM_IDS, true)) {
                continue;
            }

            $ids[] = $id;
        }

        return $ids;
    }
}
