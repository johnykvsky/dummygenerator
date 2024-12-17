<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

use DummyGenerator\Definitions\Extension\ExtensionInterface;

class InvalidExtensionInterfaceClass implements ExtensionInterface
{
    public function __construct()
    {
        throw new \RuntimeException('Problem, move on');
    }
}