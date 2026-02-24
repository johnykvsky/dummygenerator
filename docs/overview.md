# Overview

This document explains the core concepts used by the `DummyGenerator` core class and its supporting container infrastructure.

## DummyGenerator

`DummyGenerator` is the core runtime object. It:

- Resolves and executes definitions (aka "extensions") that provide data generation methods.
- Uses a container to find and cache the right definition for a requested method.
- Applies a strategy to control how calls are evaluated.
- Exposes convenience APIs like `parse()` and `withDefinition()`.

It does not define what data to generate by itself. It only orchestrates resolution, caching, and invocation of definitions.

## Container

The container is the dependency hub. It holds:

- Core services (strategy, clock, template parser).
- The definition map (the source of all DI definitions).
- The extension registry (the list of registered definition IDs).
- All definitions that can be called by `DummyGenerator`.

Internally this is a PHP-DI container wrapped by `DummyContainer`, which also supports dynamically adding definitions.

## Definition Map

The definition map is an object that stores all DI definitions (service entries) keyed by ID. It is the source of truth
for what should be in the container when it is built or rebuilt.

`DefinitionMapInterface` allows different implementations. `DefinitionMap` is the default implementation and stores the
definitions in-memory.

## Extension Registry

The extension registry is a list of definition IDs that should be considered "extensions" (definitions that implement
methods used by the generator).

`DummyGenerator` iterates this list to find a definition that implements a requested method name.

`ExtensionRegistryInterface` allows custom implementations. `ExtensionRegistry` is the default implementation.

## Container Factory

`DiContainerFactory` builds containers consistently and injects required infrastructure. It can:

- Build base/default/all containers using `DefinitionPack`.
- Build a container from explicit definitions.
- Build a container from a `DefinitionMapInterface`.

It also handles the creation of the definition map and extension registry based on the definition entries.

## Strategy

The strategy controls how generator calls are evaluated (for example, immediate execution vs. other evaluation modes).
`SimpleStrategy` is the default implementation.

## Clock

The clock provides the current time as a dependency. `SystemClockInterface` abstracts time for testability and
consistency. `SystemClock` is the default implementation.

## Extension (Definition)

An extension (definition) is any service that implements `DefinitionInterface` and exposes one or more methods
that can be called by `DummyGenerator`. The method name becomes the "token" the generator can resolve.
