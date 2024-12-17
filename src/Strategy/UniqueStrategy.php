<?php

declare(strict_types=1);

namespace DummyGenerator\Strategy;

class UniqueStrategy implements StrategyInterface
{
    /**
     * Contains all previously generated values, keyed by the Extension's function name.
     *
     * @var array<string, array<string, null>>
     */
    private array $previous = [];

    /**
     * With the unique generator you are guaranteed to never get the same two
     * values.
     *
     * <code>
     * // will never return twice the same value
     * $generator->randomElement(array(1, 2, 3));
     * </code>
     *
     * @param int $retries Maximum number of retries to find a unique value,
     *                         After which an OverflowException is thrown.
     */
    public function __construct(private readonly int $retries)
    {
    }

    public function generate(string $name, callable $callback): mixed
    {
        if (!isset($this->previous[$name])) {
            $this->previous[$name] = [];
        }

        $tries = 0;

        do {
            $response = $callback();

            ++$tries;

            if ($tries > $this->retries) {
                throw new \OverflowException(sprintf('Maximum retries of %d reached without finding a unique value', $this->retries));
            }
        } while (array_key_exists(serialize($response), $this->previous[$name]));

        $this->previous[$name][serialize($response)] = null;

        return $response;
    }
}
