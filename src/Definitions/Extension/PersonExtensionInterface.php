<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension;

interface PersonExtensionInterface extends ExtensionInterface
{
    public const string GENDER_FEMALE = 'female';
    public const string GENDER_MALE = 'male';

    /**
     * @param string|null $gender 'male', 'female' or null for any
     *
     * @example 'John Doe'
     */
    public function name(?string $gender = null): string;

    /**
     * @param string|null $gender 'male', 'female' or null for any
     *
     * @example 'John'
     */
    public function firstName(?string $gender = null): string;

    /**
     * @example 'John'
     */
    public function firstNameMale(): string;

    /**
     * @example 'Jane'
     */
    public function firstNameFemale(): string;

    /**
     * @example 'Doe'
     */
    public function lastName(): string;

    /**
     * @example 'Mrs.'
     *
     * @param string|null $gender 'male', 'female' or null for any
     */
    public function title(?string $gender = null): string;

    /**
     * @example 'Mr.'
     */
    public function titleMale(): string;

    /**
     * @example 'Mrs.'
     */
    public function titleFemale(): string;
}
