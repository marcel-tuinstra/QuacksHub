<?php

namespace App\Entity\Project;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Project;
use App\Entity\Traits\EqualsTrait;
use App\Entity\Traits\OwnerTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Entity\User;
use App\Repository\Project\TaskRepository;
use App\Utility\DateTimeUtility;
use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\Table(name: '`project_task`')]
class Task
{
    use EqualsTrait;
    use OwnerTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private ?string $description;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $completedAt = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project;

    public function __construct(User $owner, Project $project, string $description)
    {
        $this->owner       = $owner;
        $this->project     = $project;
        $this->description = $description;
    }

    // Getters
    //////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCompletedAt(): ?DateTime
    {
        return $this->completedAt;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    // Setters
    //////////////////////////////

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setCompletedAt(?DateTime $completedAt): void
    {
        $this->completedAt = $completedAt;
    }

    public function setProject(?Project $project): void
    {
        $this->project = $project;
    }

    // Actions
    //////////////////////////////

    public function markAsComplete(): void
    {
        $this->setCompletedAt(DateTimeUtility::nowUtc());
    }

    public function markAsOpen(): void
    {
        $this->setCompletedAt(null);
    }
}
