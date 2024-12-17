<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension;

interface LanguageExtensionInterface extends ExtensionInterface
{
    /**
     * @example 'fr'
     */
    public function languageCode(): string;

    /**
     * @example 'fr_FR'
     */
    public function locale(): string;
}
