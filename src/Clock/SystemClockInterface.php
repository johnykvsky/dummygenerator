<?php

declare(strict_types = 1);

namespace DummyGenerator\Clock;

use DateTimeZone;
use Psr\Clock\ClockInterface;

interface SystemClockInterface extends ClockInterface
{
    public function timezone(): DateTimeZone;
}
