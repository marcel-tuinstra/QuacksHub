<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    const ALIAS = 'u';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByEmailAndSub(string $email, string $sub): ?User
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);

        $this->andEmail($queryBuilder, $email);
        $this->andSub($queryBuilder, $sub);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findByToken(string $token): ?User
    {
        $queryBuilder = $this->createQueryBuilder(self::ALIAS);

        $this->andToken($queryBuilder, $token);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function andEmail(QueryBuilder $queryBuilder, string $email): void
    {
        $queryBuilder->andWhere(self::ALIAS . '.email = :email')
            ->setParameter('email', $email);
    }

    public function andSub(QueryBuilder $queryBuilder, string $sub): void
    {
        $queryBuilder->andWhere(self::ALIAS . '.sub = :sub')
            ->setParameter('sub', $sub);
    }

    public function andToken(QueryBuilder $queryBuilder, string $token): void
    {
        $queryBuilder->andWhere(self::ALIAS . '.token = :token')
            ->setParameter('token', $token);
    }
}
