<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Transliterator;

use DummyGenerator\Definitions\Replacer\ReplacerInterface;

interface TransliteratorAwareReplacerInterface extends ReplacerInterface
{
    public function withTransliterator(TransliteratorInterface $transliterator): ReplacerInterface;
}
