<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Transport\Event;

use App\Crm\Domain\Entity\Project;
use App\Crm\Application\Model\ProjectStatistic;

final class ProjectStatisticEvent extends AbstractProjectEvent
{
    public function __construct(
        Project $project,
        private readonly ProjectStatistic $statistic,
        private readonly ?\DateTimeInterface $begin = null,
        private readonly ?\DateTimeInterface $end = null
    )
    {
        parent::__construct($project);
    }

    public function getStatistic(): ProjectStatistic
    {
        return $this->statistic;
    }

    public function getBegin(): ?\DateTimeInterface
    {
        return $this->begin;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }
}
