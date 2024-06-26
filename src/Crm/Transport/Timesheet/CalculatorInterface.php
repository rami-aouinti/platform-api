<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Timesheet;

use App\Crm\Domain\Entity\Timesheet;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * A calculator is called before a Timesheet entity will be updated.
 * These classes will normally be used when calculating duration or rates.
 */
#[AutoconfigureTag]
interface CalculatorInterface
{
    /**
     * All necessary changes need to be applied on the given $record.
     *
     * @param array<string, array<mixed, mixed>> $changeset
     */
    public function calculate(Timesheet $record, array $changeset): void;

    /*
     * Default priority is 1000 (after all system Calculator were executed).
     * The higher the priority the later it will be executed.
     *
     * @return int
     */
    public function getPriority(): int;
}
