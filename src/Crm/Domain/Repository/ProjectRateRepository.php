<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Domain\Repository;

use App\Crm\Domain\Entity\Project;
use App\Crm\Domain\Entity\ProjectRate;
use Doctrine\ORM\EntityRepository;

/**
 * @extends \Doctrine\ORM\EntityRepository<ProjectRate>
 */
class ProjectRateRepository extends EntityRepository
{
    public function saveRate(ProjectRate $rate): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($rate);
        $entityManager->flush();
    }

    public function deleteRate(ProjectRate $rate): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($rate);
        $entityManager->flush();
    }

    /**
     * @return ProjectRate[]
     */
    public function getRatesForProject(Project $project): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('r, u, p')
            ->from(ProjectRate::class, 'r')
            ->leftJoin('r.user', 'u')
            ->leftJoin('r.project', 'p')
            ->andWhere(
                $qb->expr()->eq('r.project', ':project')
            )
            ->addOrderBy('u.alias')
            ->setParameter('project', $project)
        ;

        return $qb->getQuery()->getResult();
    }
}
