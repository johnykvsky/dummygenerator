<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface CountryExtensionInterface extends ExtensionInterface
{
    /**
     * @example 'FR'
     * @see https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
     */
    public function countryISOAlpha2(): string;

    /**
     * @example 'FRA'
     * @see https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3
     */
    public function countryISOAlpha3(): string;
}
