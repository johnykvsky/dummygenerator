<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

use DummyGenerator\Definitions\Extension\ExtensionInterface;

final class BazProvider implements ExtensionInterface
{
    public function baz(): string
    {
        return 'baz';
    }

    public function bax(): string
    {
        return 'baz';
    }
}
