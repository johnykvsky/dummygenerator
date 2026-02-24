<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

interface DefinitionMapInterface
{
    /** @return array<string, mixed> */
    public function all(): array;

    public function with(string $id, mixed $definition): \DummyGenerator\Container\DefinitionMap;

    public function set(string $id, mixed $definition): void;

    /** @param array<string, mixed> $definitions */
    public function withMany(array $definitions): \DummyGenerator\Container\DefinitionMap;

    public function without(string $id): \DummyGenerator\Container\DefinitionMap;
}
