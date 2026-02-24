<?php

declare(strict_types = 1);

namespace DummyGenerator\Strategy;

class ShortCircuitResult
{
    public function __construct(
        public readonly mixed $value
    ) {
    }
}
