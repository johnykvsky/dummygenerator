<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DateTimeInterface;
use DummyGenerator\Definitions\Extension\UuidExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

class Uuid implements UuidExtensionInterface
{
    private const string CROCKFORD_ALPHABET = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';

    public function __construct(
        protected RandomizerInterface $randomizer
    ) {
    }

    public function uuid4(): string
    {
        $data = $this->randomizer->getBytes(length: 16);
        // Set version to 0100 (UUID v4)
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        // Set variant to 10xx (RFC 4122)
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        // Convert to hexadecimal
        $hex = bin2hex($data);

        // Insert dashes to format as UUID
        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12),
        );
    }

    public function uuid7(?DateTimeInterface $dateTime = null): string
    {
        $msec = $dateTime !== null
            ? (int) ($dateTime->format('U') . $dateTime->format('v'))
            : (int) floor(microtime(true) * 1000);

        // 48-bit timestamp as 12 hex chars
        $timeHex = sprintf('%012x', $msec);

        // 10 random bytes = 20 hex chars
        $randHex = bin2hex($this->randomizer->getBytes(10));

        // Version 7: 0x70 | (12 bits random)
        $part3 = '7' . substr($randHex, 0, 3);

        // Variant 10xx: 0x80 | (byte & 0x3f)
        $varByte = (hexdec(substr($randHex, 3, 2)) & 0x3f) | 0x80;
        $part4 = sprintf('%02x', $varByte) . substr($randHex, 5, 2);

        // Part 5: remaining 12 hex chars
        $part5 = substr($randHex, 7, 12);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($timeHex, 0, 8),
            substr($timeHex, 8, 4),
            $part3,
            $part4,
            $part5,
        );
    }

    public function ulid(?DateTimeInterface $dateTime = null): string
    {
        $msec = $dateTime !== null
            ? (int) ($dateTime->format('U') . $dateTime->format('v'))
            : (int) floor(microtime(true) * 1000);

        // 48-bit timestamp -> 10 Crockford Base32 characters
        $timeChars = '';
        $time = $msec;
        for ($i = 9; $i >= 0; --$i) {
            $timeChars = self::CROCKFORD_ALPHABET[$time & 0x1f] . $timeChars;
            $time = intdiv($time, 32);
        }

        // 80-bit randomness -> 16 Crockford Base32 characters
        $randChars = '';
        for ($i = 0; $i < 16; ++$i) {
            $randChars .= self::CROCKFORD_ALPHABET[$this->randomizer->getInt(0, 31)];
        }

        return $timeChars . $randChars;
    }

    public function nilUuid(): string
    {
        return '00000000-0000-0000-0000-000000000000';
    }
}
