<?php

declare(strict_types = 1);

namespace DummyGenerator\Strategy;

use Closure;

class ValidStrategy implements StrategyInterface
{
    private Closure $validator;

    /**
     * To make sure the value meet some criteria, pass a callable that verifies the
     * output. If the validator fails, the generator will try again.
     *
     * The value validity is determined by a function passed as first argument.
     *
     * <code>
     * $values = array();
     * $evenValidator = function (int $digit): bool {
     *   return $digit % 2 === 0;
     * };
     * for ($i=0; $i < 10; $i++) {
     *   $values []= $generator->withStrategy(new ValidStrategy($evenValidator))->randomDigit();
     * }
     * print_r($values); // [0, 4, 8, 4, 2, 6, 0, 8, 8, 6]
     * </code>a
     *
     * @param callable(?mixed $value):bool $validator  A function returning true for valid values
     * @param int $retries Maximum number of retries to find a valid value,
     *                              After which an OverflowException is thrown.
     */
    public function __construct(callable $validator, private readonly int $retries = 10000)
    {
        $this->validator = $validator(...);
    }

    public function generate(string $name, callable $callback): mixed
    {
        $tries = 0;

        do {
            $response = $callback();

            ++$tries;

            if ($tries > $this->retries) {
                throw new \OverflowException(sprintf('Maximum retries of %d reached without finding a valid value', $this->retries));
            }
        } while (!$this->validator->call($this, $response));

        return $response;
    }
}
