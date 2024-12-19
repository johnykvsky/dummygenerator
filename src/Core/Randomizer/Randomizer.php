<?php

declare(strict_types=1);

namespace DummyGenerator\Core\Randomizer;

use Random\Randomizer as PhpRandomizer;

class Randomizer extends CoreRandomizer
{
    public function __construct()
    {
        $this->randomizer = new PhpRandomizer();
    }
}
