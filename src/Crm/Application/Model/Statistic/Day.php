<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Model\Statistic;

use DateTime;

/**
 * @package App\Crm\Application\Model\Statistic
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class Day extends Timesheet
{
    private int $totalDurationBillable = 0;
    private array $details = [];

    public function __construct(
        private DateTime $day,
        int $duration,
        float $rate
    ) {
        $this->setTotalDuration($duration);
        $this->setTotalRate($rate);
    }

    public function getDay(): DateTime
    {
        return $this->day;
    }

    public function getTotalDurationBillable(): int
    {
        return $this->totalDurationBillable;
    }

    public function setTotalDurationBillable(int $seconds): void
    {
        $this->totalDurationBillable = $seconds;
    }

    public function setDetails(array $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}
