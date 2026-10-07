<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface HashExtensionInterface extends ExtensionInterface
{
    /** @example 'cfcd208495d565ef66e7dff9f98764da' */
    public function md5(): string;

    /** @example 'b5d86317c2a144cd04d0d7c03b2b02666fafadf2' */
    public function sha1(): string;

    /** @example '85086017559ccc40638fcde2fecaf295e0de7ca51b7517b6aebeaaf75b4d4654' */
    public function sha256(): string;

    /** @example 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e' */
    public function sha512(): string;

    /** @example 'q3M+7dFN5A2Xo1p...' */
    public function base64(int $byteLength = 32): string;

    /** @example 'q3M-7dFN5A2Xo1p...' */
    public function base64Url(int $byteLength = 32): string;
}

