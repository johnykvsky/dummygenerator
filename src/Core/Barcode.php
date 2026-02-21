<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Calculator\EanCalculatorInterface;
use DummyGenerator\Definitions\Calculator\IsbnCalculatorInterface;
use DummyGenerator\Definitions\Extension\BarcodeExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;

class Barcode implements BarcodeExtensionInterface
{
    public function __construct(
        private RandomizerInterface $randomizer,
        private ReplacerInterface $replacer,
        private EanCalculatorInterface $eanCalculator,
        private IsbnCalculatorInterface $isbnCalculator
    ) {
    }

    private function ean(int $length = 13): string
    {
        $code = $this->replacer->numerify(str_repeat('#', $length - 1));

        return sprintf('%s%s', $code, $this->eanCalculator->checksum($code));
    }

    public function ean13(): string
    {
        return $this->ean();
    }

    public function ean8(): string
    {
        return $this->ean(8);
    }

    public function isbn10(): string
    {
        $code = $this->replacer->numerify(str_repeat('#', 9));

        return sprintf('%s%s', $code, $this->isbnCalculator->checksum($code));
    }

    public function isbn13(): string
    {
        $code = '97' . $this->randomizer->getInt(8, 9) . $this->replacer->numerify(str_repeat('#', 9));

        return sprintf('%s%s', $code, $this->eanCalculator->checksum($code));
    }
}
