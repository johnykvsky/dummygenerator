<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

class DefinitionMap
{
    /** @var array<string, mixed> */
    protected array $definitions;

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

    public function with(string $id, mixed $definition): self
    {
        $clone = clone $this;
        $clone->definitions[$id] = $definition;

        return $clone;
    }

    public function set(string $id, mixed $definition): void
    {
        $this->definitions[$id] = $definition;
    }

    /** @param array<string, mixed> $definitions */
    public function withMany(array $definitions): self
    {
        $clone = clone $this;

        foreach ($definitions as $id => $definition) {
            $clone->definitions[$id] = $definition;
        }

        return $clone;
    }

    public function without(string $id): self
    {
        $clone = clone $this;
        unset($clone->definitions[$id]);

        return $clone;
    }
}
