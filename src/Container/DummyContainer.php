<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

use DI\Container;
use DummyGenerator\Clock\SystemClockInterface;
use DummyGenerator\Definitions\DefinitionInterface;
use DummyGenerator\GeneratorInterface;
use DummyGenerator\GeneratorProxy;
use DummyGenerator\Strategy\StrategyInterface;
use DummyGenerator\Template\TemplateParserInterface;

class DummyContainer implements DummyContainerInterface
{
    /** @var array<string, DefinitionInterface> */
    private array $extensions = [];

    public function __construct(
        protected Container $container,
        /** @var array<string, mixed> */
        protected array $definitions,
        /** @var string[] */
        protected array $registry
    ) {
    }

    public function get(string $id): mixed
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    public function set(string $id, mixed $value): void
    {
        if ($id === GeneratorInterface::class) {
            $this->container->set($id, DiContainerFactory::normalizeDefinition($value));
            return;
        }

        $this->definitions[$id] = $value;
        if (!in_array($id, $this->registry, true) && !$this->isSystemId($id)) {
            $this->registry[] = $id;
        }

        $this->rebuildContainer();
    }

    public function definitions(): array
    {
        return $this->definitions;
    }

    public function registry(): array
    {
        return $this->registry;
    }

    public function withDefinition(string $id, mixed $definition): DummyContainerInterface
    {
        $definitions = $this->definitions;
        $definitions[$id] = $definition;

        return DiContainerFactory::fromDefinitions($definitions);
    }

    public function withDefinitions(array $definitions): DummyContainerInterface
    {
        $merged = $this->definitions;
        foreach ($definitions as $id => $definition) {
            $merged[$id] = $definition;
        }

        return DiContainerFactory::fromDefinitions($merged);
    }

    public function getExtension(string $method): ?DefinitionInterface
    {
        return $this->extensions[$method] ?? null;
    }

    public function setExtension(string $method, DefinitionInterface $extension): void
    {
        $this->extensions[$method] = $extension;
    }

    public function resetExtensions(): void
    {
        $this->extensions = [];
    }

    protected function rebuildContainer(): void
    {
        $generator = null;

        if ($this->container->has(GeneratorInterface::class)) {
            $existing = $this->container->get(GeneratorInterface::class);
            if ($existing instanceof GeneratorInterface && !$existing instanceof GeneratorProxy) {
                $generator = $existing;
            }
        }

        $this->container = DiContainerFactory::buildContainer($this->definitions);
        $this->registry = DiContainerFactory::buildRegistry($this->definitions);
        $this->resetExtensions();

        if ($generator !== null) {
            $this->container->set(GeneratorInterface::class, $generator);
        }
    }

    protected function isSystemId(string $id): bool
    {
        return in_array($id, [
            StrategyInterface::class,
            SystemClockInterface::class,
            TemplateParserInterface::class,
            GeneratorInterface::class,
        ], true);
    }
}
