<?php

declare(strict_types=1);

namespace Provider\pl_PL;

use DummyGenerator\Core\DateTime as BaseDateTime;
use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionTrait;

class DateTime extends BaseDateTime
{
    use GeneratorAwareExtensionTrait;

    /**
     * @var string[]
     */
    protected array $days = [
        'niedziela', 'poniedziałek', 'wtorek', 'środa', 'Czwartek', 'piątek', 'sobota',
    ];

    /**
     * @var string[]
     */
    protected array $months = [
        'styczeń', 'luty', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec',
        'sierpień', 'wrzesień', 'październik', 'listopad', 'grudzień',
    ];

    /**
     * @var string[]
     */
    protected array $monthsGenitive = [
        'stycznia', 'lutego', 'marca', 'kwietnia', 'maja', 'czerwca', 'lipca',
        'sierpnia', 'września', 'października', 'listopada', 'grudnia',
    ];

    /**
     * @var string[]
     */
    protected array $formattedDateFormat = [
        '{{dayOfMonth}}. {{monthNameGenitive}} {{year}}',
    ];

    public function monthName(\DateTimeInterface|string $until = 'now'): string
    {
        return $this->months[(int) $this->month($until) - 1];
    }

    public function monthNameGenitive(\DateTimeInterface|string $max = 'now'): string
    {
        return $this->monthsGenitive[(int) $this->month($max) - 1];
    }

    public function dayOfWeek(\DateTimeInterface|string $until = 'now'): string
    {
        return $this->days[$this->dateTime($until)->format('w')];
    }

    /**
     * @param \DateTimeInterface|string $until maximum timestamp used as random end limit, default to "now"
     *
     * @example '2'
     */
    public function dayOfMonth(\DateTimeInterface|string $until = 'now'): string
    {
        return $this->dateTime($until)->format('j');
    }

    /**
     * Full date with inflected month
     *
     * @example '16. listopada 2003'
     */
    public function formattedDate(): string
    {
        $format = $this->randomizer->randomElement($this->formattedDateFormat);

        return $this->generator->parse($format);
    }
}
