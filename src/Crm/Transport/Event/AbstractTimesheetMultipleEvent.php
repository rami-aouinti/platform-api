<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

use App\Crm\Domain\Entity\Timesheet;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Base event class to used with timesheet manipulations.
 */
abstract class AbstractTimesheetMultipleEvent extends Event
{
    /**
     * @param array<Timesheet> $timesheets
     */
    public function __construct(
        private array $timesheets
    ) {
    }

    public function getTimesheets(): array
    {
        return $this->timesheets;
    }
}
