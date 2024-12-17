<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Definitions\Extension\CompanyExtensionInterface;
use DummyGenerator\Definitions\Extension\LoremExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Company;
use DummyGenerator\Core\Lorem;
use DummyGenerator\Core\Person;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(TransliteratorInterface::class, Transliterator::class);
        $container->add(ReplacerInterface::class, Replacer::class);
        $container->add(PersonExtensionInterface::class, Person::class);
        $container->add(LoremExtensionInterface::class, Lorem::class);
        $container->add(CompanyExtensionInterface::class, Company::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testCompany(): void
    {
        self::assertNotEmpty($this->generator->company());
    }

    public function testCompanySuffix(): void
    {
        self::assertNotEmpty($this->generator->companySuffix());
    }

    public function testJobTitle(): void
    {
        self::assertNotEmpty($this->generator->jobTitle());
    }
}
