<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
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

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(CompanyExtensionInterface::class, Company::class);
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

    // Enhanced validation tests

    public function testCompanyIsString(): void
    {
        $company = $this->generator->company();

        self::assertIsString($company);
        self::assertGreaterThan(2, strlen($company));
    }

    public function testCompanyGeneratesDifferentNames(): void
    {
        $companies = [];
        for ($i = 0; $i < 20; $i++) {
            $companies[] = $this->generator->company();
        }

        $uniqueCompanies = array_unique($companies);
        self::assertGreaterThan(1, count($uniqueCompanies), 'Should generate different company names');
    }

    public function testCompanySuffixIsString(): void
    {
        $suffix = $this->generator->companySuffix();

        self::assertIsString($suffix);
        self::assertNotEmpty($suffix);
        self::assertGreaterThan(1, strlen($suffix));
    }

    public function testJobTitleIsString(): void
    {
        $jobTitle = $this->generator->jobTitle();

        self::assertIsString($jobTitle);
        self::assertGreaterThanOrEqual(2, strlen($jobTitle));
    }

    public function testJobTitleGeneratesDifferentTitles(): void
    {
        $titles = [];
        for ($i = 0; $i < 20; $i++) {
            $titles[] = $this->generator->jobTitle();
        }

        $uniqueTitles = array_unique($titles);
        self::assertGreaterThan(1, count($uniqueTitles), 'Should generate different job titles');
    }

    public function testCompanySuffixConsistency(): void
    {
        // Test that companySuffix() consistently returns valid suffixes
        for ($i = 0; $i < 10; $i++) {
            $suffix = $this->generator->companySuffix();
            self::assertIsString($suffix);
            self::assertNotEmpty($suffix);
        }
    }

    public function testCompanyNameContainsMultipleWords(): void
    {
        // Many company names contain multiple words
        $companiesWithSpaces = 0;

        for ($i = 0; $i < 20; $i++) {
            $company = $this->generator->company();
            if (str_contains($company, ' ')) {
                $companiesWithSpaces++;
            }
        }

        // At least some should have spaces (multi-word names)
        self::assertGreaterThan(0, $companiesWithSpaces);
    }

    public function testJobTitleNotEmpty(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $jobTitle = $this->generator->jobTitle();
            self::assertNotEmpty($jobTitle);
            self::assertIsString($jobTitle);
        }
    }

    public function testCompanyConsistency(): void
    {
        // Test that company generation is consistent
        for ($i = 0; $i < 10; $i++) {
            $company = $this->generator->company();
            self::assertIsString($company);
            self::assertNotEmpty($company);
        }
    }
}
