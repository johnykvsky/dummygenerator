<?php

declare(strict_types = 1);

namespace DummyGenerator;

use DummyGenerator\Definitions\DefinitionInterface;
use DummyGenerator\Exception\MissingDependencyException;
use Psr\Container\ContainerInterface;

readonly class GeneratorProxy implements GeneratorInterface
{
    public function __construct(
        protected ContainerInterface $container
    ) {
    }

    public function parse(string $string): string
    {
        return $this->requireGenerator()->parse($string);
    }

    /** @param array<int, mixed> $arguments */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->requireGenerator()->__call($name, $arguments);
    }

    protected function requireGenerator(): GeneratorInterface
    {
        try {
            $generator = $this->container->get(GeneratorInterface::class);
        } catch (\Throwable $e) {
            throw new MissingDependencyException(
                'Generator is not initialized. Create DummyGenerator first or register one in the container.',
                $e->getCode(),
                $e,
            );
        }

        if ($generator === $this || !$generator instanceof GeneratorInterface) {
            throw new MissingDependencyException('Container entry for GeneratorInterface must implement GeneratorInterface.');
        }

        return $generator;
    }

    public function withDefinition(string $name, callable|DefinitionInterface|string $value): GeneratorInterface
    {
        return $this->requireGenerator()->withDefinition($name, $value);
    }

    public function removeDefinition(string $id): GeneratorInterface
    {
        return $this->requireGenerator()->removeDefinition($id);
    }

    public function resetExtensionCache(): void
    {
        $this->requireGenerator()->resetExtensionCache();
    }
}
