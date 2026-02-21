<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

use DummyGenerator\Container\DummyContainerInterface;

final class FakeContainer implements DummyContainerInterface
{
    /** @var array<string, mixed> */
    private array $services;

    /** @var array<string, bool> */
    private array $hasOverrides;

    /**
     * @param array<string, mixed> $services
     * @param array<string, bool> $hasOverrides
     */
    public function __construct(array $services = [], array $hasOverrides = [])
    {
        $this->services = $services;
        $this->hasOverrides = $hasOverrides;
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
    }
}
