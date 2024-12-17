<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

use DummyGenerator\Definitions\Extension\ExtensionInterface;

final class FooProvider implements ExtensionInterface
{
    public function foo(): string
    {
        return 'foobar';
    }

    public function fooManChu(string $value = ''): string
    {
        return 'baz' . $value;
    }

    public function bar(): string
    {
        return 'foo';
    }
}
