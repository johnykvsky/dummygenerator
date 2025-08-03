<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface StringsExtensionInterface extends ExtensionInterface
{
    /**
     * Generate radom string from lowercase letters
     *
     * @param int $min minimum number of characters
     * @param int $max maximum number of characters
     * @param ?string $pool pool of chars to generate string from
     *
     * @example nxvhd
     */
    public function string(int $min = 3, int $max = 8, ?string $pool = null): string;
}
