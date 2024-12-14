<?php

namespace App\Repository;

use App\Entity\Investment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Investment>
 *
 * @method Investment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Investment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Investment[]    findAll()
 * @method Investment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestmentRepository extends ServiceEntityRepository
{
    const ALIAS = 'i';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Investment::class);
    }

    public function countByOwner(User $user, bool $excludeDeleted = true): int
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);
        $queryBuilder->select(sprintf('COUNT(%s.id)', self::ALIAS));

        $this->andOwner($queryBuilder, $user);

        if ($excludeDeleted) {
            $this->andDeleted($queryBuilder);
        }

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function findAllByOwner(User $user, bool $excludeDeleted = true): array
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);

        $this->andOwner($queryBuilder, $user);

        if ($excludeDeleted) {
            $this->andDeleted($queryBuilder);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function andOwner(QueryBuilder $queryBuilder, User $owner): void
    {
        $queryBuilder->andWhere(sprintf('%s.owner = :owner', self::ALIAS))
            ->setParameter('owner', $owner);
    }

    public function andDeleted(QueryBuilder $queryBuilder): void
    {
        $queryBuilder->andWhere(sprintf('%s.deletedAt IS NOT NULL', self::ALIAS));
    }
}
