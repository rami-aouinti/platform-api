<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Domain\Repository\Query;

interface BillableInterface
{
    /**
     * Returns the internal value (null = ignore billable, true = is billable, false = is not billable).
     */
    public function getBillable(): ?bool;

    /**
     * Returns true if the billable flag should be used and should match true.
     */
    public function isBillable(): bool;

    /**
     * Returns true if the billable flag should be used and should match false.
     */
    public function isNotBillable(): bool;

    /**
     * Returns true if the billable flag should NOT be used.
     */
    public function isIgnoreBillable(): bool;

    /**
     * Pas null if you want to ignore the billable flag.
     */
    public function setBillable(?bool $isBillable): void;
}
