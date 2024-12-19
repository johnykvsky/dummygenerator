<?php

declare(strict_types=1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\VersionExtensionInterface;

class Version implements VersionExtensionInterface, RandomizerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;

    /**
     * @var string[]
     */
    protected array $semverCommonPreReleaseIdentifiers = ['alpha', 'beta', 'rc'];

    public function semver(bool $preRelease = false, bool $build = false): string
    {
        return sprintf(
            '%d.%d.%d%s%s',
            $this->randomizer->getInt(0, 9),
            $this->randomizer->getInt(0, 99),
            $this->randomizer->getInt(0, 99),
            $preRelease && $this->randomizer->getBool() ? '-' . $this->semverPreReleaseIdentifier() : '',
            $build && $this->randomizer->getBool() ? '+' . $this->semverBuildIdentifier() : ''
        );
    }

    /**
     * Common pre-release identifier
     */
    private function semverPreReleaseIdentifier(): string
    {
        $ident = $this->randomizer->randomElement($this->semverCommonPreReleaseIdentifiers);

        if ($this->randomizer->getBool()) {
            return $ident;
        }

        return $ident . '.' . $this->randomizer->getInt(1, 99);
    }

    /**
     * Common random build identifier
     */
    private function semverBuildIdentifier(): string
    {
        if ($this->randomizer->getBool()) {
            // short git revision syntax: https://git-scm.com/book/en/v2/Git-Tools-Revision-Selection
            return substr(sha1(uniqid('', true)), 0, 7);
        }

        // date syntax
        return (new \DateTimeImmutable())->format('YmdHis');
    }
}