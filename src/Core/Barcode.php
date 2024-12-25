<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\EanCalculatorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\EanCalculatorAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\IsbnCalculatorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\IsbnCalculatorAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\BarcodeExtensionInterface;

class Barcode implements
    BarcodeExtensionInterface,
    RandomizerAwareExtensionInterface,
    EanCalculatorAwareExtensionInterface,
    IsbnCalculatorAwareExtensionInterface,
    ReplacerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;
    use EanCalculatorAwareExtensionTrait;
    use IsbnCalculatorAwareExtensionTrait;
    use ReplacerAwareExtensionTrait;

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
