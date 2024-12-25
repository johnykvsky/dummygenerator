<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

use DummyGenerator\Definitions\DefinitionInterface;

interface DefinitionContainerInterface
{
    public function get(string $id): DefinitionInterface;

    public function has(string $id): bool;

    public function add(string $name, callable|DefinitionInterface|string $value): void;

    public function findProcessor(string $name): null|ResolvedDefinition;
}
