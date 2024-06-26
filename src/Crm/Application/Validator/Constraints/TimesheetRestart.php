<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Validator\Constraints;

final class TimesheetRestart extends TimesheetConstraint
{
    public const START_DISALLOWED = 'kimai-timesheet-restart-01';

    protected const ERROR_NAMES = [
        self::START_DISALLOWED => 'You are not allowed to start this timesheet record.',
    ];

    public string $message = 'You are not allowed to start this timesheet record.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
