<?php

namespace App\Service;

use App\Collection\UserCollection;
use App\Entity\User;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository         $userRepository
    )
    {
        // noop
    }

    public function createFromAuth0(string $email, string $sub): ?User
    {
        $user = new User($email, $sub);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function generateToken(User $user): void
    {
        $expiration = new DateTime();
        $expiration->add(new DateInterval('PT1H')); // Token expires after 1 hour

        $user->setToken(bin2hex(random_bytes(60)));
        $user->setTokenExpiration($expiration);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getAll(): UserCollection
    {
        return new UserCollection($this->userRepository->findAll());
    }

    public function getByEmailAndSub(string $email, string $sub): ?User
    {
        return $this->userRepository->findByEmailAndSub($email, $sub);
    }

    public function getByToken(string $token): ?User
    {
        return $this->userRepository->findByToken($token);
    }
}