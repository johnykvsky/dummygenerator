<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension;

interface CompanyExtensionInterface extends ExtensionInterface
{
    /**
     * @example 'Acme Ltd'
     */
    public function company(): string;

    /**
     * @example 'Ltd'
     */
    public function companySuffix(): string;

    /**
     * @example 'Job'
     */
    public function jobTitle(): string;
}
