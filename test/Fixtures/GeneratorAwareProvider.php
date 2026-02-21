<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\GeneratorInterface;

final class GeneratorAwareProvider implements ExtensionInterface
{
    public function __construct(private GeneratorInterface $generator)
    {
    }

    public function fromParse(): string
    {
        return $this->generator->parse('{{foo}}');
    }

    public function fromCall(): string
    {
        // @phpstan-ignore-next-line
        return $this->generator->foo();
    }
}
