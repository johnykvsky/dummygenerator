<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Container;

use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Exception\MissingDependencyException;
use DummyGenerator\Test\Fixtures\FooProvider;
use DummyGenerator\Test\Fixtures\GeneratorAwareProvider;
use Psr\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class GeneratorProxyTest extends TestCase
{
    public function testDummyGeneratorCreateWorks(): void
    {
        $generator = DummyGenerator::create();

        self::assertNotEmpty($generator->uuid4());
    }

    public function testSetRegistersDefinitionForRuntimeLookup(): void
    {
        $container = DiContainerFactory::default();

        $container->set(FooProvider::class, FooProvider::class);
        $generator = new DummyGenerator($container);

        self::assertSame('foobar', $generator->foo());
    }

    public function testGeneratorProxyResolvesAfterGeneratorConstruction(): void
    {
        $container = DiContainerFactory::default();
        $container->set(FooProvider::class, FooProvider::class);
        $container->set(GeneratorAwareProvider::class, GeneratorAwareProvider::class);

        $extension = $container->get(GeneratorAwareProvider::class);

        try {
            $extension->fromParse();
            self::fail('Expected MissingDependencyException before generator initialization.');
        } catch (MissingDependencyException $exception) {
            $message = $exception->getMessage();
            self::assertTrue(
                str_contains($message, 'Generator is not initialized')
                || str_contains($message, 'Container entry for GeneratorInterface must implement GeneratorInterface.'),
                'Unexpected exception message: ' . $message,
            );
        }

        $generator = new DummyGenerator($container);

        self::assertSame('foobar', $extension->fromParse());
        self::assertSame('foobar', $extension->fromCall());
        self::assertSame('foobar', $generator->fromParse());
    }

    public function testGeneratorProxyThrowsMissingDependencyBeforeInitialization(): void
    {
        $container = DiContainerFactory::default();
        $proxy = $container->get(\DummyGenerator\GeneratorInterface::class);

        $this->expectException(MissingDependencyException::class);

        // @phpstan-ignore-next-line
        $proxy->parse('{{ foo }}');
    }

    public function testGeneratorProxyThrowsWhenContainerGetFails(): void
    {
        $container = new class implements ContainerInterface {
            public function get(string $id): mixed
            {
                throw new \RuntimeException('boom');
            }

            public function has(string $id): bool
            {
                return true;
            }
        };

        $proxy = new \DummyGenerator\GeneratorProxy($container);

        $this->expectException(MissingDependencyException::class);

        // @phpstan-ignore-next-line
        $proxy->parse('{{ foo }}');
    }
}
