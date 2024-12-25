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
}
