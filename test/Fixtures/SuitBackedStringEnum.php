<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

enum SuitBackedStringEnum: string
{
    case Hearts = 'Hearts';
    case Diamonds = 'Diamonds';
    case Clubs = 'Clubs';
    case Spades = 'Spades';
}
