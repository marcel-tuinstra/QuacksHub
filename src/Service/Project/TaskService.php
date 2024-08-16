<?php

namespace App\Service\Project;

use App\Collection\Project\TaskCollection;
use App\DTO\Project\TaskDTO;
use App\Entity\Project;
use App\Entity\Project\Task;
use App\Entity\User;
use App\Repository\Project\TaskRepository;
use App\Utility\DateTimeUtility;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TaskRepository         $taskRepository
    )
    {
        // noop
    }

    public function getTasksCountByOwner(User $owner): int
    {
        return $this->taskRepository->countByOwner($owner);
    }

    public function getCompletedTasksCountByOwner(User $owner): int
    {
        return $this->taskRepository->countCompletedByOwner($owner);
    }

    public function getNotCompletedTasksCountByOwner(User $owner): int
    {
        return $this->taskRepository->countNotCompletedByOwner($owner);
    }

    public function getTasksByProject(Project $project): TaskCollection
    {
        return new TaskCollection($this->taskRepository->findAllByProject($project));
    }

    public function create(Project $project, User $owner): ?Task
    {
        $task = new Task($owner, $project, sprintf('Sample Task %s', DateTimeUtility::nowUtcAsImmutable()->format('Y-m-d H:i:s')));
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function update(Task $task, TaskDTO $taskDTO): Task
    {
        $task->setCompletedAt($taskDTO->completedAt);
        $task->setDescription($taskDTO->description);

        // Further logic and database operations
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function delete(Task $task, bool $permanent = false): void
    {
        if ($permanent) {
            $this->entityManager->remove($task);
        } else {
            $task->setDeletedAt(DateTimeUtility::nowUtcAsImmutable());
            $this->entityManager->persist($task);
        }

        $this->entityManager->flush();
    }
}