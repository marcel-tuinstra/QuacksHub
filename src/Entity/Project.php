<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Collection\Project\TaskCollection;
use App\Entity\Interfaces\IdentifiableInterface;
use App\Entity\Interfaces\TimestampableInterface;
use App\Entity\Project\Task;
use App\Entity\Traits\EqualsTrait;
use App\Entity\Traits\OwnerTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\ProjectRepository;
use App\ValueObject\Project\Category;
use App\ValueObject\Project\Status;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: '`project`')]
class Project implements IdentifiableInterface, TimestampableInterface
{
    use EqualsTrait;
    use OwnerTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private string $title;

    #[ORM\Column(type: 'project:category')]
    private Category $category;

    #[ORM\Column(type: 'project:status')]
    private Status $status;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    protected ?DateTime $dueAt = null;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $tasks;

    public function __construct(User $owner, string $title, Category $category)
    {
        $this->owner    = $owner;
        $this->title    = $title;
        $this->category = $category;

        // Defaults
        $this->status = Status::notStarted();
        $this->tasks  = new TaskCollection();
    }

    // Getters
    //////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getDueAt(): ?DateTime
    {
        return $this->dueAt;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTasks(): TaskCollection
    {
        if (!$this->tasks instanceof TaskCollection) {
            $this->tasks = new TaskCollection($this->tasks->toArray());
        }

        return $this->tasks;
    }

    // Setters
    //////////////////////////////

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function setDueAt(?DateTime $dueAt): void
    {
        $this->dueAt = $dueAt;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    // Tasks
    //////////////////////////////

    public function addTask(Task $task): void
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }
    }

    public function removeTask(Task $task): void
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }
    }

    // Progress Calculation
    //////////////////////////////

    public function getProgress(): int
    {
        // If the project is not started, return 0% progress
        if ($this->status->isNotStarted()) {
            return 0;
        }

        // If the project is completed, return 100% progress
        if ($this->status->isCompleted()) {
            return 100;
        }

        $activeTasks    = $this->getTasks()->getActiveTasks()->count();
        $completedTasks = $this->getTasks()->getCompletedTasks()->count();

        // If there are no tasks, but the project is in progress, return a baseline progress
        if ($activeTasks === 0) {
            return 0; // Baseline progress for a project in progress but with no tasks
        }

        // Calculate the progress as a percentage of completed tasks
        return (int)(($completedTasks / $activeTasks) * 100);
    }
}
