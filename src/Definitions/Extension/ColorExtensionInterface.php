<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface ColorExtensionInterface extends ExtensionInterface
{
    /** @example '#fa3cc2' */
    public function hexColor(): string;

    /** @example '#ff0044' */
    public function safeHexColor(): string;

    /**
     * @return int[]
     *
     * @example array(0,255,122)
     */
    public function rgbColorAsArray(): array;

    /** @example '0,255,122' */
    public function rgbColor(): string;

    /** @example 'rgb(0,255,122)' */
    public function rgbCssColor(): string;

    /** @example 'rgba(0,255,122,0.8)' */
    public function rgbaCssColor(): string;

    /** @example 'blue' */
    public function safeColorName(): string;

    /** @example 'NavajoWhite' */
    public function colorName(): string;

    /** @example '340,50,20' */
    public function hslColor(): string;

    /**
     * @return int[]
     *
     * @example array(340, 50, 20)
     */
    public function hslColorAsArray(): array;
}
