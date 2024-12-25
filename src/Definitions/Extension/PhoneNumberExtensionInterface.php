<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface PhoneNumberExtensionInterface extends ExtensionInterface
{
    /** @example '555-123-546' */
    public function phoneNumber(): string;

    /** @example +27113456789 */
    public function e164PhoneNumber(): string;

    /**
     * International Mobile Equipment Identity (IMEI)
     *
     * @see http://en.wikipedia.org/wiki/International_Mobile_Station_Equipment_Identity
     * @see http://imei-number.com/imei-validation-check/
     * @example '720084494799532'
     */
    public function imei(): string;
}
