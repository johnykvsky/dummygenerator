<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
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

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(PersonExtensionInterface::class, Person::class);
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

    // Enhanced validation tests

    public function testNameFormatIncludesBothFirstAndLast(): void
    {
        $name = $this->generator->name();

        // Should have at least two parts (first and last name)
        $parts = explode(' ', $name);
        self::assertGreaterThanOrEqual(2, count($parts), "Name should have at least first and last name");
    }

    public function testNameWithMaleGenderReturnsMaleName(): void
    {
        // Get list of male first names from Person class
        $person = $this->generator->ext(\DummyGenerator\Definitions\Extension\PersonExtensionInterface::class);
        $reflection = new \ReflectionClass($person);
        $property = $reflection->getProperty('firstNameMale');
        $maleNames = $property->getValue($person);

        // Generate multiple names and check they contain male first names
        for ($i = 0; $i < 10; $i++) {
            $name = $this->generator->firstName(gender: \DummyGenerator\Definitions\Extension\PersonExtensionInterface::GENDER_MALE);
            self::assertContains($name, $maleNames, "First name $name should be in male names list");
        }
    }

    public function testNameWithFemaleGenderReturnsFemale(): void
    {
        // Get list of female first names from Person class
        $person = $this->generator->ext(\DummyGenerator\Definitions\Extension\PersonExtensionInterface::class);
        $reflection = new \ReflectionClass($person);
        $property = $reflection->getProperty('firstNameFemale');
        $femaleNames = $property->getValue($person);

        // Generate multiple names and check they contain female first names
        for ($i = 0; $i < 10; $i++) {
            $name = $this->generator->firstName(gender: \DummyGenerator\Definitions\Extension\PersonExtensionInterface::GENDER_FEMALE);
            self::assertContains($name, $femaleNames, "First name $name should be in female names list");
        }
    }

    public function testTitleMaleReturnsOnlyMaleTitles(): void
    {
        // Get male titles
        $person = $this->generator->ext(\DummyGenerator\Definitions\Extension\PersonExtensionInterface::class);
        $reflection = new \ReflectionClass($person);
        $property = $reflection->getProperty('titleMale');
        $maleTitles = $property->getValue($person);

        for ($i = 0; $i < 10; $i++) {
            $title = $this->generator->titleMale();
            self::assertContains($title, $maleTitles, "Title $title should be in male titles list");
        }
    }

    public function testTitleFemaleReturnsOnlyFemaleTitles(): void
    {
        // Get female titles
        $person = $this->generator->ext(\DummyGenerator\Definitions\Extension\PersonExtensionInterface::class);
        $reflection = new \ReflectionClass($person);
        $property = $reflection->getProperty('titleFemale');
        $femaleTitles = $property->getValue($person);

        for ($i = 0; $i < 10; $i++) {
            $title = $this->generator->titleFemale();
            self::assertContains($title, $femaleTitles, "Title $title should be in female titles list");
        }
    }

    public function testFirstNameNotEmpty(): void
    {
        $firstName = $this->generator->firstName();
        self::assertNotEmpty($firstName);
        self::assertIsString($firstName);
        self::assertGreaterThan(1, strlen($firstName));
    }

    public function testLastNameNotEmpty(): void
    {
        $lastName = $this->generator->lastName();
        self::assertNotEmpty($lastName);
        self::assertIsString($lastName);
        self::assertGreaterThan(1, strlen($lastName));
    }

    public function testTitleNotEmpty(): void
    {
        $title = $this->generator->title();
        self::assertNotEmpty($title);
        self::assertIsString($title);
    }

    public function testNameWithNullGenderReturnsAnyGender(): void
    {
        // Generate many names and check we get both genders
        $names = [];
        for ($i = 0; $i < 50; $i++) {
            $names[] = $this->generator->firstName(gender: null);
        }

        // Should have variety (not all the same)
        $uniqueNames = array_unique($names);
        self::assertGreaterThan(1, count($uniqueNames), 'Should generate different names');
    }

    public function testFullNameWithGenderMale(): void
    {
        $name = $this->generator->name(gender: \DummyGenerator\Definitions\Extension\PersonExtensionInterface::GENDER_MALE);

        // Should contain spaces (title/first/last)
        self::assertStringContainsString(' ', $name);

        // Should not be empty
        self::assertNotEmpty($name);
    }

    public function testFullNameWithGenderFemale(): void
    {
        $name = $this->generator->name(gender: \DummyGenerator\Definitions\Extension\PersonExtensionInterface::GENDER_FEMALE);

        // Should contain spaces (title/first/last)
        self::assertStringContainsString(' ', $name);

        // Should not be empty
        self::assertNotEmpty($name);
    }

    public function testStatisticalGenderBalance(): void
    {
        // Generate many first names without gender specification
        $maleCount = 0;
        $femaleCount = 0;

        $person = $this->generator->ext(\DummyGenerator\Definitions\Extension\PersonExtensionInterface::class);
        $reflection = new \ReflectionClass($person);

        $maleProp = $reflection->getProperty('firstNameMale');
        $maleNames = $maleProp->getValue($person);

        $femaleProp = $reflection->getProperty('firstNameFemale');
        $femaleNames = $femaleProp->getValue($person);

        for ($i = 0; $i < 100; $i++) {
            $name = $this->generator->firstName();
            if (in_array($name, $maleNames)) {
                $maleCount++;
            }
            if (in_array($name, $femaleNames)) {
                $femaleCount++;
            }
        }

        // Both genders should be represented (allow some variance)
        self::assertGreaterThan(10, $maleCount, 'Should have some male names');
        self::assertGreaterThan(10, $femaleCount, 'Should have some female names');
    }
}
