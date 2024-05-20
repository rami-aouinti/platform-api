<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository;

use App\Quiz\Domain\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    /**
     * @return array|float|int|mixed|object[]|string
     */
    public function findAll(): mixed
    {
        $builder = $this->createQueryBuilder('a');
        $builder->orderBy('a.text', 'ASC');
        return $builder->getQuery()->getResult();
    }
}
