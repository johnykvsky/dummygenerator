<?php

declare(strict_types=1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\AddressExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionTrait;

class Address implements
    AddressExtensionInterface,
    GeneratorAwareExtensionInterface,
    RandomizerAwareExtensionInterface,
    ReplacerAwareExtensionInterface
{
    use GeneratorAwareExtensionTrait;
    use RandomizerAwareExtensionTrait;
    use ReplacerAwareExtensionTrait;

    /**
     * @var string[]
     */
    protected array $citySuffix = [
        'Ville'
    ];

    /**
     * @var string[]
     */
    protected array $streetSuffix = [
        'Street'
    ];

    /**
     * @var string[]
     */
    protected array $cityFormats = [
        '{{firstName}}{{citySuffix}}',
    ];

    /**
     * @var string[]
     */
    protected array $streetNameFormats = [
        '{{lastName}} {{streetSuffix}}',
    ];

    /**
     * @var string[]
     */
    protected array $streetAddressFormats = [
        '{{buildingNumber}} {{streetName}}',
    ];

    /**
     * @var string[]
     */
    protected array $addressFormats = [
        '{{streetAddress}} {{postcode}} {{city}}',
    ];

    /**
     * @var string[]
     */
    protected array $buildingNumber = ['%#'];

    /**
     * @var string[]
     */
    protected array $postcode = ['#####', '##-###'];

    /**
     * @var string[]
     */
    protected array $country = ['England', 'France'];

    public function citySuffix(): string
    {
        return $this->randomizer->randomElement($this->citySuffix);
    }

    public function streetSuffix(): string
    {
        return $this->randomizer->randomElement($this->streetSuffix);
    }

    public function buildingNumber(): string
    {
        return $this->replacer->numerify($this->randomizer->randomElement($this->buildingNumber));
    }

    public function city(): string
    {
        $format = $this->randomizer->randomElement($this->cityFormats);

        return $this->generator->parse($format);
    }

    public function streetName(): string
    {
        $format = $this->randomizer->randomElement($this->streetNameFormats);

        return $this->generator->parse($format);
    }

    public function streetAddress(): string
    {
        $format = $this->randomizer->randomElement($this->streetAddressFormats);

        return $this->generator->parse($format);
    }

    public function postcode(): string
    {
        return $this->replacer->toUpper($this->replacer->bothify($this->randomizer->randomElement($this->postcode)));
    }

    public function address(): string
    {
        $format = $this->randomizer->randomElement($this->addressFormats);

        return $this->generator->parse($format);
    }

    public function country(): string
    {
        return $this->randomizer->randomElement($this->country);
    }
}
