<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Repository;

use App\Quiz\Domain\Entity\Language;
use App\Quiz\Domain\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    private $em;
    private $param;
    private $tokenStorage;
    private $language;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em, ParameterBagInterface $param, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($registry, Question::class);
        $this->em = $em;
        $this->param = $param;
        $this->tokenStorage = $tokenStorage;
        $this->language = $this->em->getReference(Language::class, $this->param->get('locale'));
    }

    public function create(): Question
    {
        $question = new Question();
        $question->setLanguage($this->language);

        return $question;
    }

    public function find($id, $lockMode = null, $lockVersion = null)
    {
        $builder = $this->createQueryBuilder('q');

        $builder->andWhere('q.id = :id');
        $builder->setParameter('id', $id);

        $builder->andWhere('q.language = :language');
        $builder->setParameter('language', $this->language);

        $builder->orderBy('q.text', 'ASC');

        return $builder->getQuery()->getOneOrNullResult();
    }

    public function findOne($id, $lockMode = null, $lockVersion = null, $isTeacher = false, $isAdmin = false)
    {
        $builder = $this->createQueryBuilder('q');

        $builder->andWhere('q.id = :id');
        $builder->setParameter('id', $id);

        $builder->andWhere('q.language = :language');
        $builder->setParameter('language', $this->language);

        if (!$isAdmin) {
            $builder->andWhere('q.created_by = :created_by');
            $builder->setParameter('created_by', $this->tokenStorage->getToken()->getUser());
        }

        $builder->orderBy('q.text', 'ASC');

        return $builder->getQuery()->getOneOrNullResult();
    }

    public function findAll(int $page = 1, $isTeacher = false, $isAdmin = false): array //Pagerfanta
    {
        $builder = $this->createQueryBuilder('q');

        $builder->andWhere('q.language = :language');
        $builder->setParameter('language', $this->language);

        if (!$isAdmin) {
            $builder->andWhere('q.created_by = :created_by');
            $builder->setParameter('created_by', $this->tokenStorage->getToken()->getUser());
        }

        $builder->orderBy('q.text', 'ASC');

        return $builder->getQuery()->getResult();
    }

    public function findAllByCategories($categories, int $page = 1, $isTeacher = false, $isAdmin = false)
    {
        $builder = $this->createQueryBuilder('q');

        $builder->andWhere('q.language = :language');
        $builder->setParameter('language', $this->language);

        if (!$isAdmin) {
            $builder->andWhere('q.created_by = :created_by');
            $builder->setParameter('created_by', $this->tokenStorage->getToken()->getUser());
        }

        $builder->innerJoin('q.categories', 'categories');
        $builder->andWhere($builder->expr()->in('categories', ':categories'))->setParameter('categories', $categories);
        // if (!$isTeacher) {
        //     $builder->andWhere('q.active = :active');
        //     $builder->setParameter('active', true);
        // }
        $builder->orderBy('q.text', 'ASC');

        return $builder->getQuery()->getResult();
    }

    public function findOneRandomByCategories($categories, $isTeacher = false, $isAdmin = false): ?Question
    {
        $builder = $this->createQueryBuilder('q');
        $builder->andWhere('q.language = :language');
        $builder->setParameter('language', $this->language);
        $builder->innerJoin('q.categories', 'categories');
        $builder->andWhere($builder->expr()->in('categories', ':categories'))->setParameter('categories', $categories);

        $questions = $builder->getQuery()->getResult();
        $question = $questions[rand(1, sizeof($questions)) - 1];

        return $question;
    }

    public function countByCategories($categories, $isTeacher = false, $isAdmin = false): int
    {
        $builder = $this->createQueryBuilder('q');

        $builder->andWhere('q.language = :language');
        $builder->setParameter('language', $this->language);

        $builder->innerJoin('q.categories', 'categories');
        $builder->andWhere($builder->expr()->in('categories', ':categories'))->setParameter('categories', $categories);

        $questions = $builder->getQuery()->getResult();

        return sizeof($questions);
    }
}
