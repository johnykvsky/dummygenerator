<?php

namespace DummyGenerator\Test\Fixtures;

use DummyGenerator\Core\Color;

class ProviderColor extends Color
{
    /** @var array<string> */
    protected array $safeColorNames = [
        'czarny', 'brązowy', 'zielony',
    ];
}