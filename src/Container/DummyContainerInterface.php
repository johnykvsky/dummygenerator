<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

use Psr\Container\ContainerInterface;

interface DummyContainerInterface extends ContainerInterface
{
    public function get(string $id): mixed;

    public function has(string $id): bool;

    public function set(string $id, mixed $value): void;
}
