<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension;

interface PaymentExtensionInterface extends ExtensionInterface
{
    /**
     * @example 'MasterCard'
     */
    public function creditCardType(): string;

    /**
     * Returns the String of a credit card number.
     *
     * @param ?string $type      Supporting any of 'Visa', 'MasterCard', 'American Express', 'Discover' and 'JCB'
     * @param bool   $formatted Set to true if the output string should contain one separator every 4 digits
     * @param string $separator Separator string for formatting card number. Defaults to dash (-).
     *
     * @example '4485480221084675'
     */
    public function creditCardNumber(?string $type = null, bool $formatted = false, string $separator = '-'): string;

    /**
     * @example 04/13
     */
    public function creditCardExpirationDate(bool $inFuture = true): string;


    /**
     * @example ['type' => 'Visa', 'number' => '4539353086362790', 'name' => 'John Smith', 'expirationDate' => '04/29']
     *
     * @param bool $valid True (by default) to get a valid expiration date, false to get a maybe valid date
     *
     * @return array<string, mixed>
     */
    public function creditCardDetails(bool $valid = true): array;

    /**
     * International Bank Account Number (IBAN)
     *
     * @see http://en.wikipedia.org/wiki/International_Bank_Account_Number
     *
     * @param string|null $alpha2    ISO 3166-1 alpha-2 country code
     * @param string $prefix    for generating bank account number of a specific bank
     */
    public function iban(string $alpha2 = null, string $prefix = ''): string;

    /**
     * Return the String of a SWIFT/BIC number
     *
     * @see    http://en.wikipedia.org/wiki/ISO_9362
     *
     * * SWIFT / BIC codes should contain:
     * * a 4-letter bank code
     * * a 2-letter country code
     * * a 2-letter or number location code
     * * a 3-letter or number branch code (optional)
     *
     * @example 'RZTIIT22263', 'INGBPLPW'
     */
    public function swiftBicNumber(): string;

    /**
     * @example 'EUR'
     * @see https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
     */
    public function currencyCode(): string;
}
