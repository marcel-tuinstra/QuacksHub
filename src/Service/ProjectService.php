<?php

namespace App\Service;

use App\Collection\ProjectCollection;
use App\DTO\ProjectDTO;
use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use App\Utility\DateTimeUtility;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ProjectRepository      $projectRepository
    )
    {
        // noop
    }

    public function getProjectsCountByOwner(User $owner): int
    {
        return $this->projectRepository->countByOwner($owner);
    }

    public function getActiveProjectsCountByOwner(User $owner): int
    {
        return $this->projectRepository->countActiveByOwner($owner);
    }

    public function getAllProjectsByOwner(User $owner, bool $excludeDeleted = true): ProjectCollection
    {
        return new ProjectCollection($this->projectRepository->findAllByOwner($owner, $excludeDeleted));
    }

    public function create(ProjectDTO $projectDTO, User $owner): ?Project
    {
        $project = new Project($owner, $projectDTO->title, $projectDTO->category);
        $project->setDescription($projectDTO->description);

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }

    public function update(Project $project, ProjectDTO $projectDTO): Project
    {
        $project->setTitle($projectDTO->title);
        $project->setCategory($projectDTO->category);
        $project->setStatus($projectDTO->status);
        $project->setDueAt($projectDTO->dueAt);
        $project->setDescription($projectDTO->description);

        // Further logic and database operations
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }

    public function delete(Project $project, bool $permanent = false): void
    {
        if ($permanent) {
            $this->entityManager->remove($project);
        } else {
            $project->setDeletedAt(DateTimeUtility::nowUtcAsImmutable());
            $this->entityManager->persist($project);
        }

        $this->entityManager->flush();
    }
}