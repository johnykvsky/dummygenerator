<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Container;

use DummyGenerator\Container\ContainerException;
use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Container\NotInContainerException;
use DummyGenerator\Container\ResolvedDefinition;
use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\Test\Fixtures\BarProvider;
use DummyGenerator\Test\Fixtures\FooProvider;
use DummyGenerator\Test\Fixtures\InvalidExtensionInterfaceClass;
use PHPUnit\Framework\TestCase;

class DefinitionContainerTest extends TestCase
{
    public function testCanCheckIfContainerHasDefinition(): void
    {
        $definitions = [
            'some_name' => fn () => 'some_value'
        ];

        $container = new DefinitionContainer($definitions);

        self::assertTrue($container->has('some_name'));
    }

    public function testCanGetDefinitionFromContainer(): void
    {
        $definitions = [
            'some_name' => fn () => new class implements ExtensionInterface {
            }
        ];

        $container = new DefinitionContainer($definitions);

        self::assertInstanceOf(ExtensionInterface::class, $container->get('some_name'));
    }

    public function testThrowsExceptionIfDefinitionNotInContainer(): void
    {
        $container = new DefinitionContainer([]);

        $this->expectException(NotInContainerException::class);
        $container->get('some_name');
    }

    public function testContainerOnlyHoldsDefinitions(): void
    {
        $testClass = new \stdClass();
        $definitions = ['some_name' => $testClass::class];

        // @phpstan-ignore-next-line
        $container = new DefinitionContainer($definitions);

        $this->expectException(ContainerException::class);
        $container->get('some_name');
    }

    public function testCanAddDefinitionToContainer(): void
    {
        $container = new DefinitionContainer([]);

        $container->add('some_name', new class implements ExtensionInterface {
        });

        self::assertTrue($container->has('some_name'));
    }

    public function testCanRemoveDefinitionFromContainer(): void
    {
        $container = new DefinitionContainer([]);

        $container->add('some_name', new class implements ExtensionInterface {
        });

        self::assertTrue($container->has('some_name'));

        $container->remove('some_name');

        self::assertFalse($container->has('some_name'));
    }

    public function testCanGetAllDefinitions(): void
    {
        $container = new DefinitionContainer([]);
        $container->add('some_name', new FooProvider());

        self::assertInstanceOf(FooProvider::class, $container->get('some_name'));
        self::assertEquals(['some_name' => new FooProvider()], $container->definitions());

        $container->add('some_name', new BarProvider());

        self::assertInstanceOf(BarProvider::class, $container->get('some_name'));
        self::assertEquals(['some_name' => new BarProvider()], $container->definitions());
    }

    public function testCanFindProcessorForMethod(): void
    {
        $definitions = [
            'some_name' => fn () => new class implements ExtensionInterface {
                public function hello(string $name): string
                {
                    return 'hello ' . $name;
                }
            }
        ];

        $container = new DefinitionContainer($definitions);

        $processor = $container->findProcessor('hello');

        self::assertInstanceOf(ResolvedDefinition::class, $processor);
        self::assertEquals('hello Johny', $processor->service->hello('Johny'));
    }

    public function testThrowsExceptionIfCallbackDefinitionDoesNotReturnClass(): void
    {
        $definitions = [
            'some_name' => fn () => true
        ];

        $container = new DefinitionContainer($definitions);

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Error while invoking callable for "some_name"');
        $container->get('some_name');
    }

    public function testThrowsExceptionIfDefinitionPointsToNonExistingClass(): void
    {
        $definitions = [
            'some_name' => 'string_that_should_be_class_name'
        ];

        $container = new DefinitionContainer($definitions);

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Could not instantiate class for "some_name". Class was not found.');
        $container->get('some_name');
    }

    public function testThrowsExceptionIfDefinitionPointsToClassThatCannotBeCreated(): void
    {
        $definitions = [
            'some_name' => InvalidExtensionInterfaceClass::class
        ];

        $container = new DefinitionContainer($definitions);

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Could not instantiate class for "some_name"');
        $container->get('some_name');
    }
}
