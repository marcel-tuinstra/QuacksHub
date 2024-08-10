<?php

namespace App\Repository\Project;

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
class TasksRepository extends ServiceEntityRepository
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

    public function findAllByOwner(User $user): array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);

        $this->andOwner($queryBuilder, $user);

        return $queryBuilder->getQuery()->getResult();
    }

    public function andOwner(QueryBuilder $queryBuilder, User $owner): void
    {
        $queryBuilder->where(sprintf('%s.owner = :owner', self::ALIAS))
            ->setParameter('owner', $owner);
    }
}
