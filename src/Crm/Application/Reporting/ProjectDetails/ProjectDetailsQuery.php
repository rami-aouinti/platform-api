<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Reporting\ProjectDetails;

use App\Crm\Domain\Entity\Project;
use App\User\Domain\Entity\User;
use DateTime;

final class ProjectDetailsQuery
{
    private ?Project $project = null;

    public function __construct(
        private DateTime $today,
        private User $user
    ) {
    }

    public function getToday(): DateTime
    {
        return $this->today;
    }

    public function getUser(): User
    {
        return $this->user;
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
