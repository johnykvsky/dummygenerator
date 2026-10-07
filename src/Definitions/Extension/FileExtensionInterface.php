<?php

declare(strict_types = 1);

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

    /**
     * Get a random file name with extension
     *
     * @param string|null $extension Specific extension to use (without or with leading dot)
     *
     * @example 'invoice.pdf'
     */
    public function fileName(?string $extension = null): string;

    /**
     * Get a random file size in bytes or formatted string
     *
     * @param int $minBytes Minimum file size in bytes
     * @param int $maxBytes Maximum file size in bytes
     * @param bool $formatted Return human-readable string like '2.5 MB'
     *
     * @example 1048576 or '1.5 MB'
     */
    public function fileSize(int $minBytes = 1024, int $maxBytes = 10485760, bool $formatted = false): int|string;

    /**
     * Get the MIME type corresponding to a file extension
     *
     * @param string $extension File extension (e.g. 'pdf' or '.pdf')
     *
     * @example 'application/pdf'
     */
    public function mimeTypeForExtension(string $extension = 'pdf'): string;
}
