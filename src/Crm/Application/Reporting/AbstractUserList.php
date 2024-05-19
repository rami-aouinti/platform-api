<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Application\Reporting;

use App\Crm\Domain\Entity\Project;
use App\Crm\Domain\Entity\Team;

/**
 * Class AbstractUserList
 *
 * @package App\Crm\Application\Reporting
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
abstract class AbstractUserList
{
    private ?\DateTimeInterface $date = null;
    private bool $decimal = false;
    private string $sumType = 'duration';
    private ?Team $team = null;
    private ?Project $project = null;

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function isDecimal(): bool
    {
        return $this->decimal;
    }

    public function setDecimal(bool $decimal): void
    {
        $this->decimal = $decimal;
    }

    public function getSumType(): string
    {
        return $this->sumType;
    }

    public function setSumType(string $sumType): void
    {
        if (!\in_array($sumType, ['duration', 'rate', 'internalRate'])) {
            throw new \InvalidArgumentException('Unknown sum type');
        }

        $this->sumType = $sumType;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team = null): void
    {
        $this->team = $team;
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
