<?php

declare(strict_types=1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\IbanCalculatorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\IbanCalculatorAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\LuhnCalculatorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\LuhnCalculatorAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\PaymentExtensionInterface;

class Payment implements
    PaymentExtensionInterface,
    GeneratorAwareExtensionInterface,
    RandomizerAwareExtensionInterface,
    IbanCalculatorAwareExtensionInterface,
    LuhnCalculatorAwareExtensionInterface,
    ReplacerAwareExtensionInterface
{
    use GeneratorAwareExtensionTrait;
    use RandomizerAwareExtensionTrait;
    use IbanCalculatorAwareExtensionTrait;
    use LuhnCalculatorAwareExtensionTrait;
    use ReplacerAwareExtensionTrait;

    public string $expirationDateFormat = 'm/y';

    /**
     * @var string[]
     */
    protected array $cardVendors = [
        'Visa', 'Visa', 'Visa', 'Visa', 'Visa',
        'MasterCard', 'MasterCard', 'MasterCard', 'MasterCard', 'MasterCard',
        'American Express', 'Discover Card', 'Visa Retired', 'JCB',
    ];

    /**
     * @var array<string, mixed> List of card brand masks for generating valid credit card numbers
     *
     * @see https://en.wikipedia.org/wiki/Payment_card_number Reference for existing prefixes
     * @see https://www.mastercard.us/en-us/issuers/get-support/2-series-bin-expansion.html MasterCard 2017 2-Series BIN Expansion
     */
    protected array $cardParams = [
        'Visa' => [
            '4539###########',
            '4556###########',
            '4916###########',
            '4532###########',
            '4929###########',
            '40240071#######',
            '4485###########',
            '4716###########',
            '4##############',
        ],
        'Visa Retired' => [
            '4539########',
            '4556########',
            '4916########',
            '4532########',
            '4929########',
            '40240071####',
            '4485########',
            '4716########',
            '4###########',
        ],
        'MasterCard' => [
            '2221###########',
            '23#############',
            '24#############',
            '25#############',
            '26#############',
            '2720###########',
            '51#############',
            '52#############',
            '53#############',
            '54#############',
            '55#############',
        ],
        'American Express' => [
            '34############',
            '37############',
        ],
        'Discover Card' => [
            '6011###########',
        ],
        'JCB' => [
            '3528###########',
            '3589###########',
        ],
    ];

    /**
     * @var array<string, mixed> list of IBAN formats, source: @see https://www.swift.com/standards/data-standards/iban
     */
    protected array $ibanFormats = [
        'AD' => [['n', 4],    ['n', 4],  ['c', 12]],
        'AE' => [['n', 3],    ['n', 16]],
        'AL' => [['n', 8],    ['c', 16]],
        'AT' => [['n', 5],    ['n', 11]],
        'AZ' => [['a', 4],    ['c', 20]],
        'BA' => [['n', 3],    ['n', 3],  ['n', 8],  ['n', 2]],
        'BE' => [['n', 3],    ['n', 7],  ['n', 2]],
        'BG' => [['a', 4],    ['n', 4],  ['n', 2],  ['c', 8]],
        'BH' => [['a', 4],    ['c', 14]],
        'BR' => [['n', 8],    ['n', 5],  ['n', 10], ['a', 1],  ['c', 1]],
        'CH' => [['n', 5],    ['c', 12]],
        'CR' => [['n', 4],    ['n', 14]],
        'CY' => [['n', 3],    ['n', 5],  ['c', 16]],
        'CZ' => [['n', 4],    ['n', 6],  ['n', 10]],
        'DE' => [['n', 8],    ['n', 10]],
        'DK' => [['n', 4],    ['n', 9],  ['n', 1]],
        'DO' => [['c', 4],    ['n', 20]],
        'EE' => [['n', 2],    ['n', 2],  ['n', 11], ['n', 1]],
        'EG' => [['n', 4],    ['n', 4],  ['n', 17]],
        'ES' => [['n', 4],    ['n', 4],  ['n', 1],  ['n', 1],  ['n', 10]],
        'FI' => [['n', 6],    ['n', 7],  ['n', 1]],
        'FR' => [['n', 5],    ['n', 5],  ['c', 11], ['n', 2]],
        'GB' => [['a', 4],    ['n', 6],  ['n', 8]],
        'GE' => [['a', 2],    ['n', 16]],
        'GI' => [['a', 4],    ['c', 15]],
        'GR' => [['n', 3],    ['n', 4],  ['c', 16]],
        'GT' => [['c', 4],    ['c', 20]],
        'HR' => [['n', 7],    ['n', 10]],
        'HU' => [['n', 3],    ['n', 4],  ['n', 1],  ['n', 15], ['n', 1]],
        'IE' => [['a', 4],    ['n', 6],  ['n', 8]],
        'IL' => [['n', 3],    ['n', 3],  ['n', 13]],
        'IS' => [['n', 4],    ['n', 2],  ['n', 6],  ['n', 10]],
        'IT' => [['a', 1],    ['n', 5],  ['n', 5],  ['c', 12]],
        'KW' => [['a', 4],    ['n', 22]],
        'KZ' => [['n', 3],    ['c', 13]],
        'LB' => [['n', 4],    ['c', 20]],
        'LI' => [['n', 5],    ['c', 12]],
        'LT' => [['n', 5],    ['n', 11]],
        'LU' => [['n', 3],    ['c', 13]],
        'LV' => [['a', 4],    ['c', 13]],
        'MC' => [['n', 5],    ['n', 5],  ['c', 11], ['n', 2]],
        'MD' => [['c', 2],    ['c', 18]],
        'ME' => [['n', 3],    ['n', 13], ['n', 2]],
        'MK' => [['n', 3],    ['c', 10], ['n', 2]],
        'MR' => [['n', 5],    ['n', 5],  ['n', 11], ['n', 2]],
        'MT' => [['a', 4],    ['n', 5],  ['c', 18]],
        'MU' => [['a', 4],    ['n', 2],  ['n', 2],  ['n', 12], ['n', 3],  ['a', 3]],
        'NL' => [['a', 4],    ['n', 10]],
        'NO' => [['n', 4],    ['n', 6],  ['n', 1]],
        'PK' => [['a', 4],    ['c', 16]],
        'PL' => [['n', 8],    ['n', 16]],
        'PS' => [['a', 4],    ['c', 21]],
        'PT' => [['n', 4],    ['n', 4],  ['n', 11], ['n', 2]],
        'RO' => [['a', 4],    ['c', 16]],
        'RS' => [['n', 3],    ['n', 13], ['n', 2]],
        'SA' => [['n', 2],    ['c', 18]],
        'SE' => [['n', 3],    ['n', 16], ['n', 1]],
        'SI' => [['n', 5],    ['n', 8],  ['n', 2]],
        'SK' => [['n', 4],    ['n', 6],  ['n', 10]],
        'SM' => [['a', 1],    ['n', 5],  ['n', 5],  ['c', 12]],
        'TN' => [['n', 2],    ['n', 3],  ['n', 13], ['n', 2]],
        'TR' => [['n', 5],    ['n', 1],  ['c', 16]],
        'VG' => [['a', 4],    ['n', 16]],
    ];

    /**
     * @var string[]
     *
     * @link https://en.wikipedia.org/wiki/ISO_4217
     * On date of 2019-09-27
     *
     * With the following exceptions:
     * SVC has been replaced by the USD in 2001: https://en.wikipedia.org/wiki/Salvadoran_col%C3%B3n
     * ZWL has been suspended since 2009: https://en.wikipedia.org/wiki/Zimbabwean_dollar
     */
    protected array $currencyCode = array(
        'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN',
        'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BOV',
        'BRL', 'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHE', 'CHF',
        'CHW', 'CLF', 'CLP', 'CNY', 'COP', 'COU', 'CRC', 'CUC', 'CUP', 'CVE',
        'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD',
        'FKP', 'GBP', 'GEL', 'GHS', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD',
        'HNL', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK', 'JMD',
        'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KYD',
        'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL', 'MGA',
        'MKD', 'MMK', 'MNT', 'MOP', 'MRU', 'MUR', 'MVR', 'MWK', 'MXN', 'MXV',
        'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB',
        'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB',
        'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SHP', 'SLE', 'SOS',
        'SRD', 'SSP', 'STN', 'SVC', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND',
        'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'USN', 'UYI',
        'UYU', 'UYW', 'UZS', 'VED', 'VES', 'VND', 'VUV', 'WST', 'XAF', 'XAG',
        'XAU', 'XBA', 'XBB', 'XBC', 'XBD', 'XCD', 'XDR', 'XOF', 'XPD', 'XPF',
        'XPT', 'XSU', 'XTS', 'XUA', 'XXX', 'YER', 'ZAR', 'ZMW', 'ZWG',
    );

    public function creditCardType(): string
    {
        return $this->randomizer->randomElement($this->cardVendors);
    }

    public function creditCardNumber(?string $type = null, bool $formatted = false, string $separator = '-'): string
    {

        if (null === $type || !array_key_exists($type, $this->cardParams)) {
            $type = $this->creditCardType();
        }

        $mask = $this->randomizer->randomElement($this->cardParams[$type]);

        $number = $this->replacer->numerify($mask);
        $number .= $this->luhnCalculator->computeCheckDigit($number);

        if ($formatted) {
            $p1 = substr($number, 0, 4);
            $p2 = substr($number, 4, 4);
            $p3 = substr($number, 8, 4);
            $p4 = substr($number, 12);
            $number = $p1 . $separator . $p2 . $separator . $p3 . $separator . $p4;
        }

        return $number;
    }

    public function currencyCode(): string
    {
        return $this->randomizer->randomElement($this->currencyCode);
    }

    public function creditCardExpirationDate(bool $inFuture = true): string
    {
        if ($inFuture) {
            return $this->generator->dateTimeBetween('now', '36 months')->format($this->expirationDateFormat);
        }

        return $this->generator->dateTimeBetween('-36 months', '36 months')->format($this->expirationDateFormat);
    }

    public function creditCardDetails(bool $valid = true): array
    {
        $type = $this->creditCardType();

        return [
            'type' => $type,
            'number' => $this->creditCardNumber($type),
            'name' => $this->generator->name(),
            'expirationDate' => $this->creditCardExpirationDate($valid),
        ];
    }

    public function iban(?string $alpha2 = null, string $prefix = ''): string
    {
        $countryCode = null === $alpha2 ? $this->randomizer->randomKey($this->ibanFormats) : $this->replacer->toUpper($alpha2);

        $format = $this->ibanFormats[$countryCode] ?? null;

        if ($format === null) {
            $length = 24;
            $format = [['n', $length]];
        } else {
            $length = 0;

            foreach ($format as $part) {
                [$class, $groupCount] = $part;
                $length += $groupCount;
            }
        }

        $expandedFormat = '';

        foreach ($format as $item) {
            [$class, $length] = $item;
            $expandedFormat .= str_repeat($class, $length);
        }

        $result = $prefix;
        $expandedFormat = substr($expandedFormat, $this->replacer->strlen($result));

        foreach (str_split($expandedFormat) as $class) {
            switch ($class) {
                default:
                case 'c':
                    $result .= $this->randomizer->getBool() ? $this->randomizer->getInt(0, 9) : $this->replacer->toUpper($this->randomizer->randomLetter());

                    break;

                case 'a':
                    $result .= $this->replacer->toUpper($this->randomizer->randomLetter());

                    break;

                case 'n':
                    $result .= $this->randomizer->getInt(0, 9);

                    break;
            }
        }

        $checksum = $this->ibanCalculator->checksum($countryCode . '00' . $result);

        return $countryCode . $checksum . $result;
    }

    public function swiftBicNumber(): string
    {
        $bankCode = $this->replacer->lexify('????');
        $countryCode = $this->randomizer->randomElement(array_keys($this->ibanFormats));
        $locationCode = $this->replacer->lexify('??');
        $branchCode = $this->randomizer->getInt(100, 999);

        return $this->replacer->toUpper($bankCode . $countryCode . $locationCode) . $branchCode;
    }
}
