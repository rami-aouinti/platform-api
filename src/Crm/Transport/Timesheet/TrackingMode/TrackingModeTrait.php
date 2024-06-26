<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Timesheet\TrackingMode;

use App\Crm\Domain\Entity\Timesheet;
use DateTimeZone;

trait TrackingModeTrait
{
    protected function getTimezone(Timesheet $timesheet): DateTimeZone
    {
        if ($timesheet->getBegin() !== null) {
            return $timesheet->getBegin()->getTimezone();
        }

        $timezone = date_default_timezone_get();

        if ($timesheet->getUser() !== null) {
            $timezone = $timesheet->getUser()->getTimezone();
        }

        return new DateTimeZone($timezone);
    }
}
