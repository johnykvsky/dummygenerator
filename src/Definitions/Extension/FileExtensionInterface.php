<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension;

interface FileExtensionInterface extends ExtensionInterface
{
    /**
     * Get a random MIME type
     *
     * @example 'video/avi'
     */
    public function mimeType(): string;

    /**
     * Get a random file extension (without a dot)
     *
     * @example avi
     */
    public function extension(): string;
}
