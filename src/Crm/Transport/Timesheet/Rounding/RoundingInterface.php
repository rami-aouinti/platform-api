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
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * Apply rounding rules to the given timesheet.
 */
#[AutoconfigureTag]
interface RoundingInterface
{
    public function roundBegin(Timesheet $record, int $minutes): void;

    public function roundEnd(Timesheet $record, int $minutes): void;

    public function roundDuration(Timesheet $record, int $minutes): void;

    public function getId(): string;
}
