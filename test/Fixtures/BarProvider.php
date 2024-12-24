<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

use DummyGenerator\Definitions\Extension\ExtensionInterface;

final class BarProvider implements ExtensionInterface
{
    public function bar(): string
    {
        return 'bar';
    }

    public function bars(): string
    {
        return 'bar';
    }
}
