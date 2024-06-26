<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository;

use App\Quiz\Domain\Entity\QuestionHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuestionHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionHistory[]    findAll()
 * @method QuestionHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionHistory::class);
    }

    public function findLastByWorkout($workout): ?QuestionHistory
    {
        return $this->createQueryBuilder('qh')
            ->andWhere('qh.workout = :workout')
            ->setParameter('workout', $workout)
            ->orderBy('qh.started_at', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findAllByWorkout($workout)
    {
        return $this->createQueryBuilder('qh')
            ->andWhere('qh.workout = :workout')
            ->setParameter('workout', $workout)
            ->orderBy('qh.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByQuizAndDate($quiz, $startedAt)
    {
        $builder = $this->createQueryBuilder('qh');

        $builder->addSelect('qh.question_id, count(qh.id) as question_count, avg(qh.question_success) as question_success, qh.question_text as question_text');

        $builder->andWhere('qh.question_success IS NOT NULL');

        // TODO select only questions of this $quiz
        $builder->innerJoin('App\Quiz\Domain\Entity\Workout', 'w', 'WITH', 'qh.workout = w.id');
        $builder->andWhere('w.quiz = :quiz_id');
        $builder->setParameter('quiz_id', $quiz->getId());

        $builder->andWhere('qh.started_at >= :started_at');
        $builder->setParameter('started_at', $startedAt);

        $builder->groupBy('qh.question_id');

        $builder->orderBy('question_success', 'ASC');
        $builder->addOrderBy('question_count', 'DESC');

        return $builder->getQuery()->getResult();
    }

    public function findAllByQuizAndSession($quiz, $session)
    {
        $builder = $this->createQueryBuilder('qh');

        $builder->addSelect('qh.question_id, count(qh.id) as question_count, avg(qh.question_success) as question_success, qh.question_text as question_text');

        $builder->andWhere('qh.question_success IS NOT NULL');

        // select only questions of this $quiz
        $builder->innerJoin('App\Quiz\Domain\Entity\Workout', 'w', 'WITH', 'qh.workout = w.id');
        $builder->andWhere('w.quiz = :quiz_id');
        $builder->setParameter('quiz_id', $quiz->getId());

        $builder->andWhere('w.session >= :session');
        $builder->setParameter('session', $session);

        $builder->groupBy('qh.question_id');

        $builder->orderBy('question_success', 'ASC');
        $builder->addOrderBy('question_count', 'DESC');

        return $builder->getQuery()->getResult();
    }

    //    /**
    //     * @return QuestionHistory[] Returns an array of QuestionHistory objects
    //     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuestionHistory
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
