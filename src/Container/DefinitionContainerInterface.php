<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

use DummyGenerator\Definitions\DefinitionInterface;

interface DefinitionContainerInterface
{
    public function get(string $id): DefinitionInterface;

    /** @return array<string, callable|DefinitionInterface|class-string<DefinitionInterface>> */
    public function definitions(): array;

    public function has(string $id): bool;

    public function add(string $name, callable|DefinitionInterface|string $value): void;

    public function remove(string $name): void;

    public function findProcessor(string $name): null|ResolvedDefinition;
}
