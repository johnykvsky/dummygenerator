<?php

declare(strict_types = 1);

namespace DummyGenerator;

use DummyGenerator\Clock\SystemClockInterface;
use DummyGenerator\Container\DefinitionMapInterface;
use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\Container\DummyContainerInterface;
use DummyGenerator\Container\ExtensionRegistryInterface;
use DummyGenerator\Definitions\DefinitionInterface;
use DummyGenerator\Definitions\Exception\DefinitionNotFound;
use DummyGenerator\Exception\MissingDependencyException;
use DummyGenerator\ProviderPack\ProviderPackInterface;
use DummyGenerator\Strategy\SimpleStrategy;
use DummyGenerator\Strategy\StrategyInterface;
use DummyGenerator\Template\TemplateParserInterface;

class DummyGenerator implements GeneratorInterface
{
    /** @var array<string, DefinitionInterface> */
    protected array $extensions = [];
    protected DummyContainerInterface $container;
    protected StrategyInterface $strategy;
    protected ExtensionRegistryInterface $registry;

    public function __construct(DummyContainerInterface $container)
    {
        if (!$container->has(StrategyInterface::class)) {
            throw new MissingDependencyException(
                'Container is missing StrategyInterface. ' .
                'Use DiContainerFactory or register one in the container.',
            );
        }

        if (!$container->has(SystemClockInterface::class)) {
            throw new MissingDependencyException(
                'Container is missing SystemClockInterface. ' .
                'Use DiContainerFactory or register one in the container.',
            );
        }

        $container->set(GeneratorInterface::class, $this);

        $this->container = $container;

        if (!$container->has(ExtensionRegistryInterface::class)) {
            throw new MissingDependencyException(
                'Container is missing ExtensionRegistryInterface. ' .
                'Use DiContainerFactory or register one in the container.',
            );
        }

        $registry = $this->container->get(ExtensionRegistryInterface::class);
        if (!$registry instanceof ExtensionRegistryInterface) {
            throw new MissingDependencyException(
                'Container entry for ExtensionRegistryInterface must implement ExtensionRegistryInterface.',
            );
        }

        $this->registry = $registry;

        $strategy = $this->container->get(StrategyInterface::class);
        if (!$strategy instanceof StrategyInterface) {
            throw new MissingDependencyException('Container entry for StrategyInterface must implement StrategyInterface.');
        }

        $clock = $this->container->get(SystemClockInterface::class);
        if (!$clock instanceof SystemClockInterface) {
            throw new MissingDependencyException('Container entry for SystemClockInterface must implement SystemClockInterface.');
        }

        $this->strategy = $strategy;
    }

    public static function create(): self
    {
        return new self(DiContainerFactory::all());
    }

    public function withProvider(ProviderPackInterface $providerPack): self
    {
        $map = $this->getDefinitionMap()->withMany($providerPack->all());
        $container = DiContainerFactory::fromDefinitionMap($map);

        return new self($container);
    }

    /**
     * Return extension stored in container with given ID
     *
     * @throws DefinitionNotFound
     */
    public function ext(string $id): DefinitionInterface
    {
        if (!$this->container->has($id)) {
            throw new DefinitionNotFound(sprintf(
                'No DummyGenerator definition with id "%s" was loaded.',
                $id,
            ));
        }

        $extension = $this->container->get($id);

        if (!$extension instanceof DefinitionInterface) {
            throw new DefinitionNotFound(sprintf(
                'Definition with id "%s" is not a DefinitionInterface.',
                $id,
            ));
        }

        return $extension;
    }

    /**
     * Returns a new Generator with an additional definition.
     *
     * @param DefinitionInterface|class-string<DefinitionInterface>|callable():DefinitionInterface $value
     */
    public function withDefinition(string $name, callable|DefinitionInterface|string $value): self
    {
        $map = $this->getDefinitionMap()->with($name, $value);
        $container = DiContainerFactory::fromDefinitionMap($map);

        return new self($container);
    }

    /**
     * Returns a new Generator without the given definition.
     */
    public function removeDefinition(string $id): self
    {
        $map = $this->getDefinitionMap()->without($id);
        $container = DiContainerFactory::fromDefinitionMap($map);

        return new self($container);
    }

    /**
     * Replaces tokens ('{{ tokenName }}') in given string with the result from the token method call.
     *
     * Supports:
     * - Simple tokens: {{ firstName }}
     * - Method calls with positional arguments: {{ numberBetween(1, 100) }}
     * - Method calls with named arguments: {{ sentence(wordCount: 10) }}
     * - String arguments: {{ dateTimeBetween('-1 year', 'now') }}
     */
    public function parse(string $string): string
    {
        if (!$this->container->has(TemplateParserInterface::class)) {
            throw new MissingDependencyException('Container is missing TemplateParserInterface.');
        }

        $parser = $this->container->get(TemplateParserInterface::class);

        if (!$parser instanceof TemplateParserInterface) {
            throw new MissingDependencyException(
                'Container entry for TemplateParserInterface must implement TemplateParserInterface.',
            );
        }

        return $parser->parse($string, fn (string $method, array $args) => $this->__call($method, $args));
    }

    public function resetExtensionCache(): void
    {
        $this->extensions = [];
    }

    /**
     * @param array<int, mixed> $arguments
     *
     * Magic method used to load proper extension for given function name (like firstName) and it's parameters
     */
    public function __call(string $name, array $arguments): mixed
    {
        if ($this->strategy instanceof SimpleStrategy) {
            return $this->process($name, $arguments);
        }

        return $this->strategy->generate($name, fn () => $this->process($name, $arguments));
    }

    /**
     * @param array<int, mixed> $arguments
     *
     * Get Extension for given method name
     */
    protected function process(string $method, array $arguments = []): mixed
    {
        return $this->findProcessor($method)->$method(...$arguments);
    }

    /**
     * Return callable for given method
     */
    protected function findProcessor(string $method): DefinitionInterface
    {
        if (isset($this->extensions[$method])) {
            return $this->extensions[$method];
        }

        foreach ($this->registry->registry() as $id) {
            if (!$this->container->has($id)) {
                continue;
            }

            $service = $this->container->get($id);

            if (!$service instanceof DefinitionInterface) {
                continue;
            }

            if (!method_exists($service, $method)) {
                continue;
            }

            $this->extensions[$method] = $service;

            return $service;
        }

        throw new \InvalidArgumentException(sprintf('Unknown method "%s"', $method));
    }

    protected function getDefinitionMap(): DefinitionMapInterface
    {
        if (!$this->container->has(DefinitionMapInterface::class)) {
            throw new MissingDependencyException(
                'Container is missing DefinitionMapInterface. ' .
                'Use DiContainerFactory or register one in the container.',
            );
        }

        $map = $this->container->get(DefinitionMapInterface::class);
        if (!$map instanceof DefinitionMapInterface) {
            throw new MissingDependencyException(
                'Container entry for DefinitionMapInterface must implement DefinitionMapInterface.',
            );
        }

        return $map;
    }
}
