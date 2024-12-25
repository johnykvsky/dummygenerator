<?php

declare(strict_types = 1);

namespace DummyGenerator\Container;

use DummyGenerator\DefinitionPack\DefinitionPack;
use DummyGenerator\DefinitionPack\DefinitionPackInterface;

class DefinitionContainerBuilder
{
    /**
     *  Return DefinitionContainer instance with only Base extensions loaded
     */
    public static function base(?DefinitionPackInterface $definitionPack = null): DefinitionContainer
    {
        if ($definitionPack === null) {
            $definitionPack = new DefinitionPack();
        }

        $instance = new DefinitionContainer();

        foreach ($definitionPack->coreDefinitions() as $id => $definition) {
            $instance->add($id, $definition);
        }

        foreach ($definitionPack->baseExtensions() as $id => $definition) {
            $instance->add($id, $definition);
        }

        return $instance;
    }

    /**
     *  Return DefinitionContainer instance with Base and Default extensions loaded
     */
    public static function default(?DefinitionPackInterface $definitionPack = null): DefinitionContainer
    {
        if ($definitionPack === null) {
            $definitionPack = new DefinitionPack();
        }

        $instance = new DefinitionContainer();

        foreach ($definitionPack->coreDefinitions() as $id => $definition) {
            $instance->add($id, $definition);
        }

        foreach ($definitionPack->baseExtensions() as $id => $definition) {
            $instance->add($id, $definition);
        }

        foreach ($definitionPack->defaultExtensions() as $id => $definition) {
            $instance->add($id, $definition);
        }

        return $instance;
    }

    /**
     *  Return DefinitionContainer instance with all extensions loaded
     */
    public static function all(?DefinitionPackInterface $definitionPack = null): DefinitionContainer
    {
        if ($definitionPack === null) {
            $definitionPack = new DefinitionPack();
        }

        $instance = new DefinitionContainer();

        foreach ($definitionPack->coreDefinitions() as $id => $definition) {
            $instance->add($id, $definition);
        }

        foreach ($definitionPack->baseExtensions() as $id => $definition) {
            $instance->add($id, $definition);
        }

        foreach ($definitionPack->calculators() as $id => $definition) {
            $instance->add($id, $definition);
        }

        foreach ($definitionPack->defaultExtensions() as $id => $definition) {
            $instance->add($id, $definition);
        }

        foreach ($definitionPack->complementaryExtensions() as $id => $definition) {
            $instance->add($id, $definition);
        }

        return $instance;
    }
}
