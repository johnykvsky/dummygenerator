<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

final class ExtensionRegistry
{
    /** @param string[] $ids */
    public function __construct(
        private array $ids,
    ) {
    }

    public function register(string $id): void
    {
        $this->ids[] = $id;
    }

    /** @return string[] */
    public function registry(): array
    {
        return $this->ids;
    }
}
