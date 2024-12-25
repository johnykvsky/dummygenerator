<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Transliterator;

use DummyGenerator\Definitions\Replacer\ReplacerInterface;

/**
 * A helper trait to be used with TransliteratorAwareReplacer.
 */
trait TransliteratorAwareReplacerTrait
{
    protected TransliteratorInterface $transliterator;

    public function withTransliterator(TransliteratorInterface $transliterator): ReplacerInterface
    {
        $instance = clone $this;

        $instance->transliterator = $transliterator;

        return $instance;
    }
}
