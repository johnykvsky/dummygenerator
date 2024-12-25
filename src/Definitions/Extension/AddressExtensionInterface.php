<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface AddressExtensionInterface extends ExtensionInterface
{
    /** @example '791 Crist Parks, Sashabury, IL 86039-9874' */
    public function address(): string;

    /** @example 'Sashabury' */
    public function city(): string;

    /** @example 'town' */
    public function citySuffix(): string;

    /** @example 'Avenue' */
    public function streetSuffix(): string;

    /** @example 86039-9874 */
    public function postcode(): string;

    /** @example 'Crist Parks' */
    public function streetName(): string;

    /** @example '791 Crist Parks' */
    public function streetAddress(): string;

    /** @example '791' */
    public function buildingNumber(): string;

    /** @example 'Japan' */
    public function country(): string;
}
