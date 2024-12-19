<?php

declare(strict_types=1);

namespace DummyGenerator;

use DummyGenerator\Container\DefinitionContainerBuilder;
use DummyGenerator\Container\DefinitionContainerInterface;
use DummyGenerator\Definitions\DefinitionInterface;
use DummyGenerator\Definitions\Exception\DefinitionNotFound;
use DummyGenerator\Definitions\Extension\AddressExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\BarcodeExtensionInterface;
use DummyGenerator\Definitions\Extension\BiasedExtensionInterface;
use DummyGenerator\Definitions\Extension\BloodExtensionInterface;
use DummyGenerator\Definitions\Extension\ColorExtensionInterface;
use DummyGenerator\Definitions\Extension\CompanyExtensionInterface;
use DummyGenerator\Definitions\Extension\CoordinatesExtensionInterface;
use DummyGenerator\Definitions\Extension\CountryExtensionInterface;
use DummyGenerator\Definitions\Extension\DateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\FileExtensionInterface;
use DummyGenerator\Definitions\Extension\HashExtensionInterface;
use DummyGenerator\Definitions\Extension\InternetExtensionInterface;
use DummyGenerator\Definitions\Extension\LanguageExtensionInterface;
use DummyGenerator\Definitions\Extension\LoremExtensionInterface;
use DummyGenerator\Definitions\Extension\NumberExtensionInterface;
use DummyGenerator\Definitions\Extension\PaymentExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Extension\PhoneNumberExtensionInterface;
use DummyGenerator\Definitions\Extension\TextExtensionInterface;
use DummyGenerator\Definitions\Extension\UserAgentExtensionInterface;
use DummyGenerator\Definitions\Extension\VersionExtensionInterface;
use DummyGenerator\Strategy\SimpleStrategy;
use DummyGenerator\Strategy\StrategyInterface;

/**
 * @mixin AddressExtensionInterface
 * @mixin BarcodeExtensionInterface
 * @mixin BiasedExtensionInterface
 * @mixin BloodExtensionInterface
 * @mixin ColorExtensionInterface
 * @mixin CompanyExtensionInterface
 * @mixin CoordinatesExtensionInterface
 * @mixin CountryExtensionInterface
 * @mixin DateTimeExtensionInterface
 * @mixin FileExtensionInterface
 * @mixin HashExtensionInterface
 * @mixin InternetExtensionInterface
 * @mixin LanguageExtensionInterface
 * @mixin LoremExtensionInterface
 * @mixin NumberExtensionInterface
 * @mixin PaymentExtensionInterface
 * @mixin PersonExtensionInterface
 * @mixin PhoneNumberExtensionInterface
 * @mixin TextExtensionInterface
 * @mixin UserAgentExtensionInterface
 * @mixin VersionExtensionInterface
 */
class DummyGenerator
{
    /**
     * @var array<string, DefinitionInterface>
     */
    protected array $extensions = [];
    private DefinitionContainerInterface $container;
    private StrategyInterface $strategy;

    public function __construct(DefinitionContainerInterface $container = null, StrategyInterface $strategy = null)
    {
        $this->container = $container ?: DefinitionContainerBuilder::base();
        $this->strategy = $strategy ?: new SimpleStrategy();
    }

    /**
     * Returns new Generator with given strategy and same extensions
     */
    public function withStrategy(StrategyInterface $strategy): self
    {
        return new self($this->container, $strategy);
    }

    public function usedStrategy(string $strategy): bool
    {
        return $this->strategy instanceof $strategy;
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
                $id
            ));
        }

        $extension = $this->container->get($id);

        return $this->handleAwareness($extension);
    }

    /**
     * Replaces tokens ('{{ tokenName }}') in given string with the result from the token method call
     */
    public function parse(string $string): string
    {
        $callback = function ($matches) {
            return $this->process($matches[1]);
        };

        $replaced = preg_replace_callback('/{{\s?(\w+|[\w\\\]+->\w+?)\s?}}/u', $callback, $string);

        return !empty($replaced) ? $replaced : '';
    }

    /**
     * @param array<int, mixed> $arguments
     *
     * Magic method used to load proper extension for given function name (like firstName) and it's parameters
     */
    public function __call(string $name, array $arguments): mixed
    {
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

        $extension = $this->container->findProcessor($method);

        if ($extension !== null) {
            $extension = $this->handleAwareness($extension);

            $this->extensions[$method] = $extension;

            return $extension;
        }

        throw new \InvalidArgumentException(sprintf('Unknown method "%s"', $method));
    }

    private function handleAwareness(DefinitionInterface $extension): DefinitionInterface
    {
        if ($extension instanceof GeneratorAwareExtensionInterface) {
            $extension = $extension->withGenerator($this);
        }
        return $extension;
    }
}