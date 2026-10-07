<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface PaymentExtensionInterface extends ExtensionInterface
{
    /** @example 'MasterCard' */
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

    /** @example 04/13 */
    public function creditCardExpirationDate(bool $inFuture = true): string;

    /**
     * @param bool $valid True (by default) to get a valid expiration date, false to get a maybe valid date
     * @return array{type: string, number: string, name: string, expirationDate: string, cvv: string}
     *
     * @example ['type' => 'Visa', 'number' => '4539353086362790', 'name' => 'John Smith', 'expirationDate' => '04/29', 'cvv' => '352']
     */
    public function creditCardDetails(bool $valid = true): array;

    /**
     * Get credit card CVV/CVC code (3 digits, or 4 digits for American Express)
     *
     * @param string|null $cardType Optional card vendor name
     *
     * @example '352'
     */
    public function creditCardCvv(?string $cardType = null): string;

    /**
     * International Bank Account Number (IBAN)
     *
     * @param string|null $alpha2    ISO 3166-1 alpha-2 country code
     * @param string $prefix    for generating bank account number of a specific bank
     *
     * @see http://en.wikipedia.org/wiki/International_Bank_Account_Number
     */
    public function iban(?string $alpha2 = null, string $prefix = ''): string;

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
     * @example 'RZTIIT22263', 'INGBPLPW'
     */
    public function swiftBicNumber(): string;

    /**
     * @example 'EUR'
     * @see https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
     */
    public function currencyCode(): string;

    /**
     * Return a random currency symbol
     *
     * @example '$'
     */
    public function currencySymbol(): string;

    /**
     * Return a random currency name
     *
     * @example 'US Dollar'
     */
    public function currencyName(): string;

    /**
     * Return a random monetary price
     *
     * @param float $min Minimum price
     * @param float $max Maximum price
     * @param int $decimals Decimal places
     *
     * @example 49.99
     */
    public function price(float $min = 0.0, float $max = 1000.0, int $decimals = 2): float;
}
