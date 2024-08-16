<?php

namespace App\Collection\Project;

use App\Collection\ObjectCollection;
use App\Entity\Project\Task;
use Doctrine\Common\Collections\Collection;

/**
 * @extends ObjectCollection<int, Task>
 */
class TaskCollection extends ObjectCollection implements Collection
{
    protected ?string $supportedClass = Task::class;

    /**
     * Get all completed tasks.
     *
     * @return TaskCollection
     */
    public function getActiveTasks(): TaskCollection
    {
        return new self($this->filter(fn(Task $task) => $task->getDeletedAt() === null)->toArray());
    }

    /**
     * Get all completed tasks.
     *
     * @return TaskCollection
     */
    public function getCompletedTasks(): TaskCollection
    {
        return new self($this->filter(fn(Task $task) => $task->getCompletedAt() !== null)->toArray());
    }

    /**
     * Get all incomplete tasks.
     *
     * @return TaskCollection
     */
    public function getIncompleteTasks(): TaskCollection
    {
        return new self($this->filter(fn(Task $task) => $task->getCompletedAt() === null)->toArray());
    }
}