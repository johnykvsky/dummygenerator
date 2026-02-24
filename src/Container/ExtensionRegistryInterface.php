<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

interface ExtensionRegistryInterface
{
    public function register(string $id): void;

    /** @return string[] */
    public function registry(): array;
}
