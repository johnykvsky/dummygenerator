<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Definitions\Extension\BloodExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Blood;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class BloodTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(BloodExtensionInterface::class, Blood::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testBloodType(): void
    {
        self::assertNotEmpty($this->generator->bloodType());
    }

    public function testBloodRh(): void
    {
        self::assertNotEmpty($this->generator->bloodRh());
    }

    public function testBloodGroup(): void
    {
        $bloodGroup = $this->generator->bloodGroup();
        
        self::assertTrue(str_ends_with($bloodGroup, '+') || str_ends_with($bloodGroup, '-'));
    }
}