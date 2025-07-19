<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

enum SuitBackedIntEnum: int
{
    case Hearts = 1;
    case Diamonds = 2;
    case Clubs = 3;
    case Spades = 4;
}
