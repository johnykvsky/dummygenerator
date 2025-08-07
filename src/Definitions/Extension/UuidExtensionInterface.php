<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface UuidExtensionInterface extends ExtensionInterface
{
    /**
     * Get uuid v4
     *
     * @example 0a8397e9-028c-4b42-a57b-26ed54b2fe2d
     */
    public function uuid4(): string;
}
