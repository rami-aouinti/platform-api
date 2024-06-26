<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Model\Statistic;

use App\User\Domain\Entity\User;

/**
 * @package App\Crm\Application\Model\Statistic
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class UserYear
{
    public function __construct(
        private User $user,
        private Year $year
    ) {
    }

    public function getYear(): Year
    {
        return $this->year;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDuration(): int
    {
        return $this->year->getDuration();
    }

    public function getBillableDuration(): int
    {
        return $this->year->getBillableDuration();
    }

    public function getRate(): float
    {
        return $this->year->getRate();
    }

    public function getBillableRate(): float
    {
        return $this->year->getBillableRate();
    }

    public function getInternalRate(): float
    {
        return $this->year->getInternalRate();
    }
}
