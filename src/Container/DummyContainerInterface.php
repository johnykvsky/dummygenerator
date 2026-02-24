<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

use DummyGenerator\Definitions\DefinitionInterface;
use Psr\Container\ContainerInterface;

interface DummyContainerInterface extends ContainerInterface
{
    public function get(string $id): mixed;

    public function has(string $id): bool;

    public function set(string $id, mixed $value): void;

    /** @return array<string, mixed> */
    public function definitions(): array;

    /** @return string[] */
    public function registry(): array;

    public function withDefinition(string $id, mixed $definition): self;

    /** @param array<string, mixed> $definitions */
    public function withDefinitions(array $definitions): self;

    public function getExtension(string $method): ?DefinitionInterface;

    public function setExtension(string $method, DefinitionInterface $extension): void;

    public function resetExtensions(): void;
}
