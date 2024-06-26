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
 * This event can be used, to dynamically add meta fields to timesheets
 */
final class TimesheetMetaDefinitionEvent extends Event
{
    public function __construct(
        private Timesheet $entity
    ) {
    }

    public function getEntity(): Timesheet
    {
        return $this->entity;
    }
}
