<?php

declare(strict_types=1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;

class Person implements PersonExtensionInterface, GeneratorAwareExtensionInterface, RandomizerAwareExtensionInterface
{
    use GeneratorAwareExtensionTrait;
    use RandomizerAwareExtensionTrait;

    /**
     * @var string[]
     */
    protected array $titleFormat = [
        '{{titleMale}}',
        '{{titleFemale}}',
    ];

    /**
     * @var string[]
     */
    protected array $firstNameFormat = [
        '{{firstNameMale}}',
        '{{firstNameFemale}}',
    ];

    /**
     * @var string[]
     */
    protected array $maleNameFormats = [
        '{{firstNameMale}} {{lastName}}',
    ];

    /**
     * @var string[]
     */
    protected array $femaleNameFormats = [
        '{{firstNameFemale}} {{lastName}}',
    ];

    /**
     * @var string[]
     */
    protected array $firstNameMale = [
        'John', 'Harry', 'Olivier'
    ];

    /**
     * @var string[]
     */
    protected array $firstNameFemale = [
        'Jane', 'Katy', 'Anna'
    ];

    /**
     * @var string[]
     */
    protected array $lastName = ['Doe', 'Smith', 'White'];

    /**
     * @var string[]
     */
    protected array $titleMale = ['Mr.', 'Dr.', 'Prof.'];

    /**
     * @var string[]
     */
    protected array $titleFemale = ['Mrs.', 'Ms.', 'Miss', 'Dr.', 'Prof.'];

    public function name(?string $gender = null): string
    {
        if ($gender === static::GENDER_MALE) {
            $format = $this->randomizer->randomElement($this->maleNameFormats);
        } elseif ($gender === static::GENDER_FEMALE) {
            $format = $this->randomizer->randomElement($this->femaleNameFormats);
        } else {
            $format = $this->randomizer->randomElement(array_merge($this->maleNameFormats, $this->femaleNameFormats));
        }

        return $this->generator->parse($format);
    }

    public function firstName(?string $gender = null): string
    {
        if ($gender === static::GENDER_MALE) {
            return $this->firstNameMale();
        }

        if ($gender === static::GENDER_FEMALE) {
            return $this->firstNameFemale();
        }

        return $this->generator->parse($this->randomizer->randomElement($this->firstNameFormat));
    }

    public function firstNameMale(): string
    {
        return $this->randomizer->randomElement($this->firstNameMale);
    }

    public function firstNameFemale(): string
    {
        return $this->randomizer->randomElement($this->firstNameFemale);
    }

    public function lastName(): string
    {
        return $this->randomizer->randomElement($this->lastName);
    }

    public function title(?string $gender = null): string
    {
        if ($gender === static::GENDER_MALE) {
            return $this->titleMale();
        }

        if ($gender === static::GENDER_FEMALE) {
            return $this->titleFemale();
        }

        return $this->generator->parse($this->randomizer->randomElement($this->titleFormat));
    }

    public function titleMale(): string
    {
        return $this->randomizer->randomElement($this->titleMale);
    }

    public function titleFemale(): string
    {
        return $this->randomizer->randomElement($this->titleFemale);
    }
}
