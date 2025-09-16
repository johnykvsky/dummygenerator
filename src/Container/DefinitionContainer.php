<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

use DummyGenerator\Definitions\Calculator\EanCalculatorInterface;
use DummyGenerator\Definitions\Calculator\IbanCalculatorInterface;
use DummyGenerator\Definitions\Calculator\IsbnCalculatorInterface;
use DummyGenerator\Definitions\Calculator\LuhnCalculatorInterface;
use DummyGenerator\Definitions\DefinitionInterface;
use DummyGenerator\Definitions\Extension\Awareness\EanCalculatorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\IbanCalculatorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\IsbnCalculatorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\LuhnCalculatorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\RandomizerAwareReplacerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorAwareReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;

final class DefinitionContainer implements DefinitionContainerInterface
{
    /** @var array<callable(): DefinitionInterface|DefinitionInterface|class-string<DefinitionInterface>> $definitions */
    private array $definitions;

    /** @var array<string, DefinitionInterface> */
    private array $services = [];

    /**
     * Create a container object with a set of definitions.
     * The array value MUST produce an object that implements DefinitionInterface.
     *
     * @param array<string, callable|DefinitionInterface|class-string<DefinitionInterface>> $definitions
     */
    public function __construct(array $definitions = [])
    {
        $this->definitions = $definitions;
    }

    /**
     * Retrieve a definition from the container.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws ContainerException
     * @throws NotInContainerException
     */
    public function get(string $id): DefinitionInterface
    {
        if (array_key_exists($id, $this->services)) {
            return $this->services[$id];
        }

        if (!$this->has($id)) {
            throw new NotInContainerException(sprintf(
                'There is not service with id "%s" in the container.',
                $id,
            ));
        }

        $definition = $this->definitions[$id];

        return $this->services[$id] = $this->getService($id, $definition);
    }

    /**
     * Check if the container contains a given identifier.
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->definitions);
    }

    /**
     * Return all definitions
     *
     * @return array<string, callable|DefinitionInterface|class-string<DefinitionInterface>>
     */
    public function definitions(): array
    {
        return $this->definitions;
    }

    /**
     * Add new definition
     *
     * @param DefinitionInterface|class-string<DefinitionInterface>|callable(): DefinitionInterface $value
     */
    public function add(string $name, callable|DefinitionInterface|string $value): void
    {
        $this->definitions[$name] = $value;

        if (isset($this->services[$name])) {
            unset($this->services[$name]);
        }
    }

    public function remove(string $name): void
    {
        if (isset($this->definitions[$name])) {
            unset($this->definitions[$name]);
        }

        if (isset($this->services[$name])) {
            unset($this->services[$name]);
        }
    }

    /**
     * Find proper Extension for given method
     */
    public function findProcessor(string $name): null|ResolvedDefinition
    {
        foreach ($this->definitions as $id => $definition) {
            $service = $this->getService($id, $definition);

            if (method_exists($service, $name)) {
                return new ResolvedDefinition($name, $id, $service);
            }
        }

        return null;
    }

    /**
     * Get the service from a definition.
     *
     * @param callable(): DefinitionInterface|DefinitionInterface|class-string<DefinitionInterface> $definition
     */
    private function getService(string $id, callable|object|string $definition): DefinitionInterface
    {
        if ($definition instanceof DefinitionInterface) {
            return $this->handleAwareness($definition);
        }

        if (is_callable($definition)) {
            try {
                return $this->handleAwareness($definition());
            } catch (\Throwable $e) {
                throw new ContainerException(
                    sprintf('Error while invoking callable for "%s"', $id),
                    0,
                    $e,
                );
            }
        }

        if (is_string($definition) && class_exists($definition)) {
            try {
                $class = new $definition();

                if ($class instanceof DefinitionInterface) {
                    return $this->handleAwareness($class);
                }

                throw new ContainerException(sprintf('Class for "%s" is not implementing DefinitionInterface', $id));
            } catch (\Throwable $e) {
                throw new ContainerException(sprintf('Could not instantiate class for "%s"', $id), 0, $e);
            }
        }

        throw new ContainerException(sprintf(
            'Could not instantiate class for "%s". Class was not found or not implementing DefinitionInterface.',
            $id,
        ));
    }

    private function handleAwareness(DefinitionInterface $extension): DefinitionInterface
    {
        if ($extension instanceof TransliteratorAwareReplacerInterface) {
            /** @var TransliteratorInterface $transliterator */
            $transliterator = $this->get(TransliteratorInterface::class);
            $extension = $extension->withTransliterator($transliterator);
        }

        if ($extension instanceof RandomizerAwareReplacerInterface) {
            /** @var RandomizerInterface $randomizer */
            $randomizer = $this->get(RandomizerInterface::class);
            $extension = $extension->withRandomizer($randomizer);
        }

        if ($extension instanceof RandomizerAwareExtensionInterface) {
            /** @var RandomizerInterface $randomizer */
            $randomizer = $this->get(RandomizerInterface::class);
            $extension = $extension->withRandomizer($randomizer);
        }

        if ($extension instanceof ReplacerAwareExtensionInterface) {
            /** @var ReplacerInterface $replacer */
            $replacer = $this->get(ReplacerInterface::class);
            $extension = $extension->withReplacer($replacer);
        }

        if ($extension instanceof EanCalculatorAwareExtensionInterface) {
            /** @var EanCalculatorInterface $calculator */
            $calculator = $this->get(EanCalculatorInterface::class);
            $extension = $extension->withEanCalculator($calculator);
        }

        if ($extension instanceof IbanCalculatorAwareExtensionInterface) {
            /** @var IbanCalculatorInterface $calculator */
            $calculator = $this->get(IbanCalculatorInterface::class);
            $extension = $extension->withIbanCalculator($calculator);
        }

        if ($extension instanceof IsbnCalculatorAwareExtensionInterface) {
            /** @var IsbnCalculatorInterface $calculator */
            $calculator = $this->get(IsbnCalculatorInterface::class);
            $extension = $extension->withIsbnCalculator($calculator);
        }

        if ($extension instanceof LuhnCalculatorAwareExtensionInterface) {
            /** @var LuhnCalculatorInterface $calculator */
            $calculator = $this->get(LuhnCalculatorInterface::class);
            $extension = $extension->withLuhnCalculator($calculator);
        }

        return $extension;
    }
}
