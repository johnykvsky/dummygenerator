<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

use DummyGenerator\Container\DummyContainerInterface;
use DummyGenerator\Definitions\DefinitionInterface;

final class FakeContainer implements DummyContainerInterface
{
    /** @var array<string, mixed> */
    private array $services;

    /** @var string[] */
    private array $registry;

    /** @var array<string, bool> */
    private array $hasOverrides;

    /**
     * @param array<string, mixed> $services
     * @param array<string, bool> $hasOverrides
     */
    public function __construct(array $services = [], array $hasOverrides = [], array $registry = [])
    {
        $this->services = $services;
        $this->hasOverrides = $hasOverrides;
        $this->registry = $registry !== [] ? $registry : array_keys($services);
    }

    public function get(string $id): mixed
    {
        return $this->services[$id] ?? null;
    }

    public function has(string $id): bool
    {
        if (array_key_exists($id, $this->hasOverrides)) {
            return $this->hasOverrides[$id];
        }

        return array_key_exists($id, $this->services);
    }

    public function set(string $id, mixed $value): void
    {
        $this->services[$id] = $value;
        if (!in_array($id, $this->registry, true)) {
            $this->registry[] = $id;
        }
    }

    public function definitions(): array
    {
        return $this->services;
    }

    public function registry(): array
    {
        return $this->registry;
    }

    public function withDefinition(string $id, mixed $definition): DummyContainerInterface
    {
        $services = $this->services;
        $services[$id] = $definition;

        return new self($services, $this->hasOverrides, $this->registry);
    }

    public function withDefinitions(array $definitions): DummyContainerInterface
    {
        $services = $this->services;
        foreach ($definitions as $id => $definition) {
            $services[$id] = $definition;
        }

        return new self($services, $this->hasOverrides, $this->registry);
    }

    public function getExtension(string $method): ?DefinitionInterface
    {
        return null;
    }

    public function setExtension(string $method, DefinitionInterface $extension): void
    {
    }

    public function resetExtensions(): void
    {
    }
}
