<?php

namespace App\Repository\Project;

use App\Entity\Project;
use App\Entity\Project\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    const ALIAS = 'pt';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function countByOwner(User $user): int
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->select(sprintf('COUNT(%s.id)', self::ALIAS));

        $this->andOwner($queryBuilder, $user);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function countCompletedByOwner(User $user): int
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->select(sprintf('COUNT(%s.id)', self::ALIAS));

        $this->andOwner($queryBuilder, $user);
        $this->andCompleted($queryBuilder);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function countNotCompletedByOwner(User $user): int
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->select(sprintf('COUNT(%s.id)', self::ALIAS));

        $this->andOwner($queryBuilder, $user);
        $this->andNotCompleted($queryBuilder);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function findAllByProject(Project $project): array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);

        $this->andProject($queryBuilder, $project);

        return $queryBuilder->getQuery()->getResult();
    }

    public function andOwner(QueryBuilder $queryBuilder, User $owner): void
    {
        $queryBuilder->andWhere(sprintf('%s.owner = :owner', self::ALIAS))
            ->setParameter('owner', $owner);
    }

    public function andProject(QueryBuilder $queryBuilder, Project $project): void
    {
        $queryBuilder->andWhere(sprintf('%s.project = :project', self::ALIAS))
            ->setParameter('project', $project);
    }

    public function andCompleted(QueryBuilder $queryBuilder): void
    {
        $queryBuilder->andWhere(sprintf('%s.completedAt IS NOT NULL', self::ALIAS));
    }

    public function andNotCompleted(QueryBuilder $queryBuilder): void
    {
        $queryBuilder->andWhere(sprintf('%s.completedAt IS NULL', self::ALIAS));
    }
}
