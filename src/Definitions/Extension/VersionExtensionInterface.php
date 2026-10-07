<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface VersionExtensionInterface extends ExtensionInterface
{
    /**
     * Get a version number in semantic versioning syntax 2.0.0. (https://semver.org/spec/v2.0.0.html)
     *
     * @param bool $preRelease Pre-release parts may be randomly included
     * @param bool $build      Build parts may be randomly included
     *
     * @example 1.0.0
     * @example 1.0.0-alpha.1
     * @example 1.0.0-alpha.1+b71f04d
     */
    public function semver(bool $preRelease = false, bool $build = false): string;

    /**
     * Get a random git commit hash (SHA-1)
     *
     * @param bool $short If true, returns 7 characters; otherwise full 40 characters
     *
     * @example 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4'
     * @example 'e3b0c44'
     */
    public function gitCommitHash(bool $short = false): string;
}

