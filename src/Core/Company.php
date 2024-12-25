<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\CompanyExtensionInterface;

class Company implements CompanyExtensionInterface, GeneratorAwareExtensionInterface, RandomizerAwareExtensionInterface
{
    use GeneratorAwareExtensionTrait;
    use RandomizerAwareExtensionTrait;

    /** @var string[]  */
    protected array $formats = [
        '{{lastName}} {{companySuffix}}',
    ];

    /** @var string[]  */
    protected array $companySuffix = ['Ltd'];

    /** @var string[]  */
    protected array $jobTitleFormat = [
        '{{word}}',
    ];

    public function company(): string
    {
        $format = $this->randomizer->randomElement($this->formats);

        return $this->generator->parse($format);
    }

    public function companySuffix(): string
    {
        return $this->randomizer->randomElement($this->companySuffix);
    }

    public function jobTitle(): string
    {
        $format = $this->randomizer->randomElement($this->jobTitleFormat);

        return $this->generator->parse($format);
    }
}
