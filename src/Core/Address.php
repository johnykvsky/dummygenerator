<?php

declare(strict_types = 1);

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

    /** @var string[] */
    protected array $cityPrefix = ['North', 'East', 'West', 'South', 'New', 'Lake', 'Port'];

    /** @var string[] */
    protected array $citySuffix = [
        'town', 'ton', 'land', 'ville', 'berg', 'burgh', 'borough', 'bury', 'view', 'port', 'mouth', 'stad', 'furt', 'chester', 'mouth', 'fort', 'haven', 'side', 'shire',
    ];

    /** @var string[] */
    protected array $streetSuffix = [
        'Street', 'Avenue', 'Alley', 'Park', 'Drive', 'Lane', 'Square', 'Hill',
    ];

    /** @var string[] */
    protected array $cityFormats = [
        '{{cityPrefix}} {{firstName}}{{citySuffix}}',
        '{{cityPrefix}} {{firstName}}',
        '{{firstName}}{{citySuffix}}',
        '{{lastName}}{{citySuffix}}',
    ];

    /** @var string[] */
    protected array $streetNameFormats = [
        '{{firstName}} {{streetSuffix}}',
        '{{lastName}} {{streetSuffix}}',
    ];

    /** @var string[] */
    protected array $streetAddressFormats = [
        '{{buildingNumber}} {{streetName}}',
    ];

    /** @var string[] */
    protected array $addressFormats = [
        '{{streetAddress}} {{postcode}} {{city}}',
    ];

    /** @var string[] */
    protected array $buildingNumber = ['%####', '%###', '%##', '%#'];

    /** @var string[] */
    protected array $postcode = ['#####', '##-###'];

    /** @var string[] */
    protected array $country = ['England', 'France'];

    public function cityPrefix(): string
    {
        return $this->randomizer->randomElement($this->cityPrefix);
    }

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
