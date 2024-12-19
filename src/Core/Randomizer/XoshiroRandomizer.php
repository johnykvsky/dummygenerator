<?php

declare(strict_types=1);

namespace DummyGenerator\Core\Randomizer;

use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer as PhpRandomizer;

class XoshiroRandomizer extends CoreRandomizer
{
    protected PhpRandomizer $randomizer;

    public function __construct(int $seed)
    {
        $this->randomizer = new PhpRandomizer(new Xoshiro256StarStar($seed));
    }
}
