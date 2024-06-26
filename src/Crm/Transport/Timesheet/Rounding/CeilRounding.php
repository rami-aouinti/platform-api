<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Timesheet\Rounding;

use App\Crm\Domain\Entity\Timesheet;

final class CeilRounding implements RoundingInterface
{
    public function getId(): string
    {
        return 'ceil';
    }

    public function roundBegin(Timesheet $record, int $minutes): void
    {
        if ($minutes <= 0) {
            return;
        }

        $timestamp = $record->getBegin()->getTimestamp();
        $seconds = $minutes * 60;
        $diff = $timestamp % $seconds;

        if ($diff === 0) {
            return;
        }

        $newBegin = clone $record->getBegin();
        $newBegin->setTimestamp($timestamp - $diff + $seconds);
        $record->setBegin($newBegin);
    }

    public function roundEnd(Timesheet $record, int $minutes): void
    {
        if ($minutes <= 0) {
            return;
        }

        $timestamp = $record->getEnd()->getTimestamp();
        $seconds = $minutes * 60;
        $diff = $timestamp % $seconds;

        if ($diff === 0) {
            return;
        }

        $newEnd = clone $record->getEnd();
        $newEnd->setTimestamp($timestamp - $diff + $seconds);
        $record->setEnd($newEnd);
    }

    public function roundDuration(Timesheet $record, int $minutes): void
    {
        if ($minutes <= 0) {
            return;
        }

        $timestamp = $record->getDuration() ?? 0;
        $seconds = $minutes * 60;
        $diff = $timestamp % $seconds;

        if ($diff === 0) {
            return;
        }

        $record->setDuration($timestamp - $diff + $seconds);
    }
}
