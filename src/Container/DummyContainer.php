<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

use DI\Container;
use DummyGenerator\DummyGenerator;
use DummyGenerator\GeneratorInterface;
use DummyGenerator\GeneratorProxy;

class DummyContainer implements DummyContainerInterface
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
        if ($id === GeneratorInterface::class) {
            $this->container->set($id, DiContainerFactory::normalizeDefinition($value));
            return;
        }

        $this->definitionMap->set($id, $value);
        $this->registry->register($id);
        $this->rebuildContainer();
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

        $this->container = DiContainerFactory::buildContainer($this->definitionMap, $this->registry);

        if ($generator !== null) {
            $this->container->set(GeneratorInterface::class, $generator);
            $this->resetGeneratorCache($generator);
        }
    }

    protected function resetGeneratorCache(GeneratorInterface $generator): void
    {
        if (!$generator instanceof DummyGenerator) {
            return;
        }

        $generator->resetExtensionCache();
    }
}
