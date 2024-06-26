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
use App\Crm\Transport\Timesheet\RoundingService;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

final class DefaultMode extends AbstractTrackingMode
{
    public function __construct(
        private RoundingService $rounding
    ) {
    }

    public function canEditBegin(): bool
    {
        return true;
    }

    public function canEditEnd(): bool
    {
        return true;
    }

    public function canEditDuration(): bool
    {
        return true;
    }

    public function canUpdateTimesWithAPI(): bool
    {
        return true;
    }

    public function getId(): string
    {
        return 'default';
    }

    public function canSeeBeginAndEndTimes(): bool
    {
        return true;
    }

    public function getEditTemplate(): string
    {
        return 'timesheet/edit-default.html.twig';
    }

    public function create(Timesheet $timesheet, ?Request $request = null): void
    {
        parent::create($timesheet, $request);

        if ($timesheet->getBegin() === null) {
            $timesheet->setBegin(new DateTime('now', $this->getTimezone($timesheet)));
        }

        $this->rounding->roundBegin($timesheet);

        if (!$timesheet->isRunning()) {
            $this->rounding->roundEnd($timesheet);

            if ($timesheet->getDuration() !== null) {
                $this->rounding->roundDuration($timesheet);
            }
        }
    }
}
