<?php

declare(strict_types=1);

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Model;

use App\User\Domain\Entity\User;
use App\Crm\Application\Model\Statistic\Month;

/**
 * Class UserStatistic
 *
 * @package App\Crm\Application\Model
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
class UserStatistic extends TimesheetCountedStatistic
{
    public function __construct(private User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function addValuesFromMonth(Month $month): void
    {
        $this->setDuration($this->getDuration() + $month->getDuration());
        $this->setDurationBillable($this->getDurationBillable() + $month->getBillableDuration());
        $this->setRate($this->getRate() + $month->getRate());
        $this->setRateBillable($this->getRateBillable() + $month->getBillableRate());
        $this->setInternalRate($this->getInternalRate() + $month->getInternalRate());
    }
}
