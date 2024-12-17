<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Transliterator;

use DummyGenerator\Definitions\DefinitionInterface;

interface TransliteratorInterface extends DefinitionInterface
{
    public function transliterate(string $string): string;
}
