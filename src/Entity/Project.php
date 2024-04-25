<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Interfaces\IdentifiableInterface;
use App\Entity\Interfaces\TimestampableInterface;
use App\Entity\Traits\EqualsTrait;
use App\Entity\Traits\OwnerTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\ProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
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
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    // Getters
    //////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    // Setters
    //////////////////////////////

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
