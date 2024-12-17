<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension;

interface BloodExtensionInterface extends ExtensionInterface
{
    /**
     * Get an actual blood type
     *
     * @example 'AB'
     */
    public function bloodType(): string;

    /**
     * Get a random resis value
     *
     * @example '+'
     */
    public function bloodRh(): string;

    /**
     * Get a full blood group
     *
     * @example 'AB+'
     */
    public function bloodGroup(): string;
}
