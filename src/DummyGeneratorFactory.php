<?php

declare(strict_types = 1);

namespace DummyGenerator;

use DummyGenerator\Container\DefinitionContainerBuilder;

class DummyGeneratorFactory
{
    public static function create(): DummyGenerator
    {
        return new DummyGenerator(DefinitionContainerBuilder::all());
    }
}
