<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

use DI\Container;
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

class DiContainerFactory
{
    protected const array SYSTEM_IDS = [
        StrategyInterface::class,
        SystemClockInterface::class,
        TemplateParserInterface::class,
        GeneratorInterface::class,
        DefinitionMapInterface::class,
        ExtensionRegistryInterface::class,
    ];

    public static function base(
        ?DefinitionPackInterface $definitionPack = null,
        bool $withTemplateParser = false
    ): DummyContainerInterface {
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
    ): DummyContainerInterface {
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
    ): DummyContainerInterface {
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
    public static function custom(array $definitions, bool $withTemplateParser = true): DummyContainerInterface
    {
        $definitions = self::withInfrastructure($definitions, $withTemplateParser);

        $map = self::buildDefinitionMap($definitions);

        return self::fromDefinitionMap($map);
    }

    public static function fromDefinitionMap(DefinitionMapInterface $map): DummyContainerInterface
    {
        $definitions = $map->all();
        $registry = self::buildExtensionRegistry($definitions);

        $container = self::buildContainer($map, $registry);

        return new DummyContainer($container, $map, $registry);
    }

    public static function buildContainer(DefinitionMapInterface $map, ExtensionRegistryInterface $registry): Container
    {
        $definitions = $map->all();

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true);
        $containerBuilder->useAttributes(true);

        $normalized = self::normalizeDefinitions($definitions);
        $normalized[DefinitionMapInterface::class] = value($map);
        $normalized[ExtensionRegistryInterface::class] = value($registry);

        $containerBuilder->addDefinitions($normalized);

        return $containerBuilder->build();
    }

    /**
     * @param array<string, mixed> $definitions
     * @return array<string, mixed>
     */
    protected static function normalizeDefinitions(array $definitions): array
    {

        return array_map(static fn ($definition) => self::normalizeDefinition($definition), $definitions);
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
    protected static function withInfrastructure(array $definitions, bool $withTemplateParser): array
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

        if (!array_key_exists(DefinitionMapInterface::class, $definitions)) {
            $definitions[DefinitionMapInterface::class] = DefinitionMap::class;
        }

        if (!array_key_exists(ExtensionRegistryInterface::class, $definitions)) {
            $definitions[ExtensionRegistryInterface::class] = ExtensionRegistry::class;
        }

        return $definitions;
    }

    /**
     * @param array<string, mixed> $definitions
     * @return array<int, string>
     */
    protected static function resolveProcessorIds(array $definitions): array
    {
        $ids = [];

        $definitions = array_keys($definitions);
        foreach ($definitions as $id) {
            if (in_array($id, self::SYSTEM_IDS, true)) {
                continue;
            }

            $ids[] = $id;
        }

        return $ids;
    }

    /** @param array<string, mixed> $definitions */
    protected static function buildDefinitionMap(array $definitions): DefinitionMapInterface
    {
        $definition = $definitions[DefinitionMapInterface::class] ?? DefinitionMap::class;

        if ($definition instanceof DefinitionMapInterface) {
            return $definition;
        }

        if (is_string($definition) && class_exists($definition)) {
            $map = new $definition($definitions);
            if ($map instanceof DefinitionMapInterface) {
                return $map;
            }
        }

        if (is_callable($definition)) {
            $map = $definition($definitions);
            if ($map instanceof DefinitionMapInterface) {
                return $map;
            }
        }

        return new DefinitionMap($definitions);
    }

    /** @param array<string, mixed> $definitions */
    protected static function buildExtensionRegistry(array $definitions): ExtensionRegistryInterface
    {
        $ids = array_merge(
            self::resolveProcessorIds($definitions),
            self::SYSTEM_IDS,
        );

        $definition = $definitions[ExtensionRegistryInterface::class] ?? ExtensionRegistry::class;

        if ($definition instanceof ExtensionRegistryInterface) {
            $existing = $definition->registry();
            foreach ($ids as $id) {
                if (!in_array($id, $existing, true)) {
                    $definition->register($id);
                }
            }

            return $definition;
        }

        if (is_string($definition) && class_exists($definition)) {
            $registry = new $definition($ids);
            if ($registry instanceof ExtensionRegistryInterface) {
                return $registry;
            }
        }

        if (is_callable($definition)) {
            $registry = $definition($ids);
            if ($registry instanceof ExtensionRegistryInterface) {
                return $registry;
            }
        }

        return new ExtensionRegistry($ids);
    }
}
