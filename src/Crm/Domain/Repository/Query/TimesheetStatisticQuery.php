<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Domain\Repository\Query;

use App\Crm\Domain\Entity\Project;
use App\User\Domain\Entity\User;

final class TimesheetStatisticQuery
{
    private ?Project $project = null;

    /**
     * @param array<User> $users
     */
    public function __construct(
        private readonly \DateTimeInterface $begin,
        private readonly \DateTimeInterface $end,
        private array $users
    ) {
    }

    public function getBegin(): \DateTimeInterface
    {
        return $this->begin;
    }

    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): void
    {
        $this->project = $project;
    }
}
