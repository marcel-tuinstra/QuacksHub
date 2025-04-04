<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    const ALIAS = 'p';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function countByOwner(User $user): int
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->select(sprintf('COUNT(%s.id)', self::ALIAS));

        $this->andOwner($queryBuilder, $user);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function countActiveByOwner(User $user): int
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->select(sprintf('COUNT(%s.id)', self::ALIAS));

        $this->andOwner($queryBuilder, $user);
        $this->andActive($queryBuilder);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function findAllByOwner(User $user, $excludeDeleted = true): array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);

        $this->andOwner($queryBuilder, $user);

        if ($excludeDeleted) {
            $this->andActive($queryBuilder);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function andOwner(QueryBuilder $queryBuilder, User $owner): void
    {
        $queryBuilder->andWhere(sprintf('%s.owner = :owner', self::ALIAS))
            ->setParameter('owner', $owner);
    }

    public function andActive(QueryBuilder $queryBuilder): void
    {
        $queryBuilder->andWhere(sprintf('%s.deletedAt IS NULL', self::ALIAS));
    }
}
