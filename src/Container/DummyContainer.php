<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

use DI\Container;
use DummyGenerator\GeneratorInterface;

readonly class DummyContainer implements DummyContainerInterface
{
    public function __construct(
        protected Container $container,
        protected DefinitionMap $definitionMap,
        protected ExtensionRegistry $registry
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
        $this->container->set($id, DiContainerFactory::normalizeDefinition($value));
        if ($id === GeneratorInterface::class) {
            return;
        }

        $this->definitionMap->set($id, $value);
        $this->registry->register($id);
    }
}
