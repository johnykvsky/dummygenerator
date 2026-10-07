<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface UuidExtensionInterface extends ExtensionInterface
{
    /**
     * Get uuid v4
     *
     * @example 0a8397e9-028c-4b42-a57b-26ed54b2fe2d
     */
    public function uuid4(): string;

    /**
     * Get RFC 9562 time-ordered UUIDv7
     *
     * @example 018ec25e-7a1b-7888-825b-2d7c5885e7a9
     */
    public function uuid7(?\DateTimeInterface $dateTime = null): string;

    /**
     * Get 26-character Crockford Base32 Universally Unique Lexicographically Sortable Identifier (ULID)
     *
     * @example 01ARZ3NDEKTSV4RRFFQ69G5FAV
     */
    public function ulid(?\DateTimeInterface $dateTime = null): string;

    /**
     * Get RFC 4122 / RFC 9562 Nil UUID
     *
     * @example 00000000-0000-0000-0000-000000000000
     */
    public function nilUuid(): string;
}

