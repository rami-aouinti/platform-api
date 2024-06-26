<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @package App\Crm\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[ORM\Table(name: 'platform_crm_activities_rates')]
#[ORM\UniqueConstraint(columns: ['user_id', 'activity_id'])]
#[ORM\Entity(repositoryClass: 'App\Crm\Domain\Repository\ActivityRateRepository')]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(['user', 'activity'], ignoreNull: false)]
#[Serializer\ExclusionPolicy('all')]
class ActivityRate implements RateInterface
{
    use Rate;

    #[ORM\ManyToOne(targetEntity: Activity::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull]
    private ?Activity $activity = null;

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function getScore(): int
    {
        return 5;
    }
}
