<?php

namespace App\Service;

use App\Collection\InvestmentCollection;
use App\DTO\InvestmentDTO;
use App\Entity\Investment;
use App\Entity\User;
use App\Repository\InvestmentRepository;
use App\Utility\DateTimeUtility;
use Doctrine\ORM\EntityManagerInterface;

class InvestmentService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly InvestmentRepository   $investmentRepository
    )
    {
        // noop
    }

    public function getInvestmentsCountByOwner(User $owner, bool $excludeDeleted = false): int
    {
        return $this->investmentRepository->countByOwner($owner, $excludeDeleted);
    }

    public function getAllInvestmentsByOwner(User $owner, bool $excludeDeleted = true): InvestmentCollection
    {
        return new InvestmentCollection($this->investmentRepository->findAllByOwner($owner, $excludeDeleted));
    }

    public function create(InvestmentDTO $investmentDTO, User $owner): ?Investment
    {
        $investment = new Investment($owner, $investmentDTO->name, $investmentDTO->category, $investmentDTO->amount, $investmentDTO->rate, $investmentDTO->startDate);

        // Optional
        $investment->setCompany($investmentDTO->company);
        $investment->setUrl($investmentDTO->url);
        $investment->setEndDate($investmentDTO->endDate);

        $this->entityManager->persist($investment);
        $this->entityManager->flush();

        return $investment;
    }

    public function update(Investment $investment, InvestmentDTO $investmentDTO): Investment
    {
        // Required
        $investment->setName($investmentDTO->name);
        $investment->setCategory($investmentDTO->category);
        $investment->setAmount($investmentDTO->amount);
        $investment->setRate($investmentDTO->rate);
        $investment->setStartDate($investmentDTO->startDate);

        // Optional
        $investment->setCompany($investmentDTO->company);
        $investment->setUrl($investmentDTO->url);
        $investment->setEndDate($investmentDTO->endDate);

        // Further logic and database operations
        $this->entityManager->persist($investment);
        $this->entityManager->flush();

        return $investment;
    }

    public function delete(Investment $investment, bool $permanent = false): void
    {
        if ($permanent) {
            $this->entityManager->remove($investment);
        } else {
            $investment->setDeletedAt(DateTimeUtility::nowUtcAsImmutable());
            $this->entityManager->persist($investment);
        }

        $this->entityManager->flush();
    }
}