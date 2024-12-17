<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\Person;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
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
        $this->generator = new DummyGenerator($container);
    }

    public function testName(): void
    {
        self::assertNotEmpty($this->generator->name());

        self::assertNotEmpty($this->generator->name(gender: PersonExtensionInterface::GENDER_MALE));

        self::assertNotEmpty($this->generator->name(gender: PersonExtensionInterface::GENDER_FEMALE));
    }

    public function testFirstName(): void
    {
        self::assertNotEmpty($this->generator->firstName());

        self::assertNotEmpty($this->generator->firstName(gender: PersonExtensionInterface::GENDER_MALE));

        self::assertNotEmpty($this->generator->firstName(gender: PersonExtensionInterface::GENDER_FEMALE));
    }

    public function testFirstNameMale(): void
    {
        self::assertNotEmpty($this->generator->firstNameMale());
    }

    public function testFirstNameFemale(): void
    {
        self::assertNotEmpty($this->generator->firstNameFemale());
    }

    public function testLastName(): void
    {
        self::assertNotEmpty($this->generator->lastName());
    }

    public function testTitle(): void
    {
        self::assertNotEmpty($this->generator->title());

        self::assertNotEmpty($this->generator->title(gender: PersonExtensionInterface::GENDER_MALE));

        self::assertNotEmpty($this->generator->title(gender: PersonExtensionInterface::GENDER_FEMALE));
    }

    public function testTitleMale(): void
    {
        self::assertNotEmpty($this->generator->titleMale());
    }

    public function testTitleFemale(): void
    {
        self::assertNotEmpty($this->generator->titleFemale());
    }
}
