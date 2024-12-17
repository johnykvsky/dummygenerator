<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension;

interface InternetExtensionInterface extends ExtensionInterface
{
    /**
     * @example 'jdoe@acme.biz'
     */
    public function email(): string;

    /**
     * @example 'jdoe@example.com'
     */
    public function safeEmail(): string;

    /**
     * @example 'jdoe@gmail.com'
     */
    public function freeEmail(): string;

    /**
     * @example 'jdoe@dawson.com'
     */
    public function companyEmail(): string;

    /**
     * @example 'gmail.com'
     */
    public function freeEmailDomain(): string;

    /**
     * @example 'example.org'
     */
    public function safeEmailDomain(): string;

    /**
     * @example 'jdoe'
     */
    public function userName(): string;

    /**
     * @example 'fY4èHdZv68'
     */
    public function password(int $minLength = 6, int $maxLength = 20): string;

    /**
     * @example 'tiramisu.com'
     */
    public function domainName(): string;

    /**
     * @example 'faber'
     */
    public function domainWord(): string;

    /**
     * @example 'com'
     */
    public function tld(): string;

    /**
     * @example 'http://www.runolfsdottir.com/'
     */
    public function url(): string;

    /**
     * @example 'aut-repellat-commodi-vel-itaque-nihil-id-saepe-nostrum'
     */
    public function slug(int $nbWords = 6, bool $variableNbWords = true): string;

    /**
     * @example '237.149.115.38'
     */
    public function ipv4(): string;

    /**
     * @example '35cd:186d:3e23:2986:ef9f:5b41:42a4:e6f1'
     */
    public function ipv6(): string;

    /**
     * @example '10.1.1.17'
     */
    public function localIpv4(): string;

    /**
     * @example '32:F1:39:2F:D6:18'
     */
    public function macAddress(): string;
}
