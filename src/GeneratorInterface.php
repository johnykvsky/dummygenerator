<?php

declare(strict_types = 1);

namespace DummyGenerator;

interface GeneratorInterface
{
    public function parse(string $string): string;

    /** @param array<int, mixed> $arguments */
    public function __call(string $name, array $arguments): mixed;
}
