<?php

declare(strict_types=1);

namespace Provider\pl_PL;

use DummyGenerator\Core\Company as BaseCompany;
use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionTrait;

class Company extends BaseCompany
{
    use GeneratorAwareExtensionTrait;

    /**
     * @var array<string>
     */
    protected array $formats = [
        '{{lastName}}',
        '{{lastName}}',
        '{{lastName}} {{companySuffix}}',
        '{{lastName}} {{companySuffix}}',
        '{{lastName}} {{companySuffix}}',
        '{{lastName}} {{companySuffix}}',
        '{{companyPrefix}} {{lastName}}',
        '{{lastName}}-{{lastName}}',
    ];

    /**
     * @var array<string>
     */
    protected array $companySuffix = ['S.A.', 'i syn', 'sp. z o.o.', 'sp. j.', 'sp. p.', 'sp. k.', 'S.K.A', 's. c.', 'P.P.O.F'];

    /**
     * @var array<string>
     */
    protected array $companyPrefix = ['Grupa', 'Fundacja', 'Stowarzyszenie', 'Spółdzielnia'];

    /**
     * @example 'Grupa'
     */
    public function companyPrefix(): string
    {
        return $this->randomizer->randomElement($this->companyPrefix);
    }

    /**
     * Register of the National Economy
     *
     * @see http://pl.wikipedia.org/wiki/REGON
     *
     * @return string 9 digit number
     */
    public function regon(): string
    {
        $weights = [8, 9, 2, 3, 4, 5, 6, 7];
        $regionNumber = $this->generator->numberBetween(0, 49) * 2 + 1;
        $result = [(int) ($regionNumber / 10), $regionNumber % 10];

        for ($i = 2, $size = count($weights); $i < $size; ++$i) {
            $result[$i] = $this->generator->randomDigit();
        }
        $checksum = 0;

        for ($i = 0, $size = count($result); $i < $size; ++$i) {
            $checksum += $weights[$i] * $result[$i];
        }
        $checksum %= 11;

        if ($checksum === 10) {
            $checksum = 0;
        }
        $result[] = $checksum;

        return implode('', $result);
    }

    /**
     * Register of the National Economy, local entity number
     *
     * @see http://pl.wikipedia.org/wiki/REGON
     *
     * @return string 14 digit number
     */
    public function regonLocal(): string
    {
        $weights = [2, 4, 8, 5, 0, 9, 7, 3, 6, 1, 2, 4, 8];
        $result = str_split($this->regon());

        for ($i = count($result), $size = count($weights); $i < $size; ++$i) {
            $result[$i] = $this->generator->randomDigit();
        }
        $checksum = 0;

        for ($i = 0, $size = count($result); $i < $size; ++$i) {
            $checksum += $weights[$i] * (int) $result[$i];
        }
        $checksum %= 11;

        if ($checksum === 10) {
            $checksum = 0;
        }
        $result[] = $checksum;

        return implode('', $result);
    }
}
