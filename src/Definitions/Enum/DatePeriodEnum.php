<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Enum;

enum DatePeriodEnum
{
    case PAST_DATE;
    case FUTURE_DATE;
    case ANY_DATE;
}
