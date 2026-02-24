<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Generator;

use DummyGenerator\Clock\SystemClock;
use DummyGenerator\Clock\SystemClockInterface;
use DummyGenerator\Definitions\Exception\DefinitionNotFound;
use DummyGenerator\Exception\MissingDependencyException;
use DummyGenerator\GeneratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Strategy\SimpleStrategy;
use DummyGenerator\Strategy\StrategyInterface;
use DummyGenerator\Template\TemplateParser;
use DummyGenerator\Template\TemplateParserInterface;
use DummyGenerator\Test\Fixtures\FakeContainer;
use PHPUnit\Framework\TestCase;

final class DummyGeneratorExceptionTest extends TestCase
{
    /** @return array<string, mixed> */
    private function baseServices(): array
    {
        return [
            StrategyInterface::class => new SimpleStrategy(),
            SystemClockInterface::class => new SystemClock(),
            TemplateParserInterface::class => new TemplateParser(),
        ];
    }

    public function testMissingStrategyThrows(): void
    {
        $container = new FakeContainer($this->baseServices(), [
            StrategyInterface::class => false,
        ]);

        $this->expectException(MissingDependencyException::class);
        $this->expectExceptionMessage('Container is missing StrategyInterface.');

        new DummyGenerator($container);
    }

    public function testMissingSystemClockThrows(): void
    {
        $container = new FakeContainer($this->baseServices(), [
            SystemClockInterface::class => false,
        ]);

        $this->expectException(MissingDependencyException::class);
        $this->expectExceptionMessage('Container is missing SystemClockInterface.');

        new DummyGenerator($container);
    }

    public function testStrategyWrongTypeThrows(): void
    {
        $services = $this->baseServices();
        $services[StrategyInterface::class] = new \stdClass();
        $container = new FakeContainer($services);

        $this->expectException(MissingDependencyException::class);
        $this->expectExceptionMessage('Container entry for StrategyInterface must implement StrategyInterface.');

        new DummyGenerator($container);
    }

    public function testSystemClockWrongTypeThrows(): void
    {
        $services = $this->baseServices();
        $services[SystemClockInterface::class] = new \stdClass();
        $container = new FakeContainer($services);

        $this->expectException(MissingDependencyException::class);
        $this->expectExceptionMessage('Container entry for SystemClockInterface must implement SystemClockInterface.');

        new DummyGenerator($container);
    }

    public function testParseMissingTemplateParserThrows(): void
    {
        $services = $this->baseServices();
        unset($services[TemplateParserInterface::class]);
        $container = new FakeContainer($services, [
            TemplateParserInterface::class => false,
        ]);

        $generator = new DummyGenerator($container);

        $this->expectException(MissingDependencyException::class);
        $this->expectExceptionMessage('Container is missing TemplateParserInterface.');

        $generator->parse('{{ firstName }}');
    }

    public function testParseWrongTemplateParserTypeThrows(): void
    {
        $services = $this->baseServices();
        $services[TemplateParserInterface::class] = new \stdClass();
        $container = new FakeContainer($services);

        $generator = new DummyGenerator($container);

        $this->expectException(MissingDependencyException::class);
        $this->expectExceptionMessage('Container entry for TemplateParserInterface must implement TemplateParserInterface.');

        $generator->parse('{{ firstName }}');
    }

    public function testExtMissingDefinitionThrows(): void
    {
        $container = new FakeContainer($this->baseServices());
        $generator = new DummyGenerator($container);

        $this->expectException(DefinitionNotFound::class);
        $this->expectExceptionMessage('No DummyGenerator definition with id "missing" was loaded.');

        $generator->ext('missing');
    }

    public function testExtWrongTypeThrows(): void
    {
        $services = $this->baseServices();
        $services['bad'] = new \stdClass();
        $container = new FakeContainer($services);
        $generator = new DummyGenerator($container);

        $this->expectException(DefinitionNotFound::class);
        $this->expectExceptionMessage('Definition with id "bad" is not a DefinitionInterface.');

        $generator->ext('bad');
    }

    public function testUnknownMethodThrowsInvalidArgument(): void
    {
        $container = new FakeContainer($this->baseServices());
        $generator = new DummyGenerator($container);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown method "noSuchMethod"');

        // @phpstan-ignore-next-line
        $generator->noSuchMethod();
    }
}
