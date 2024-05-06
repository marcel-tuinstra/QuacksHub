<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Interfaces\IdentifiableInterface;
use App\Entity\Interfaces\TimestampableInterface;
use App\Entity\Traits\EqualsTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements IdentifiableInterface, TimestampableInterface
{
    use EqualsTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $nickname = null;

    #[ORM\Column(length: 255)]
    private string $sub;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $tokenExpiration = null;

    public function __construct(string $email, string $sub)
    {
        $this->email = $email;
        $this->sub   = $sub;
    }

    // Getters
    //////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function getSub(): ?string
    {
        return $this->sub;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getTokenExpiration(): ?DateTime
    {
        return $this->tokenExpiration;
    }

    // Setters
    //////////////////////////////

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setNickname(?string $nickname = null): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function setTokenExpiration(?DateTime $tokenExpiration): static
    {
        $this->tokenExpiration = $tokenExpiration;

        return $this;
    }

    // Checkers
    //////////////////////////////

    public function isTokenExpired(): bool
    {
        return $this->tokenExpiration !== null && $this->tokenExpiration->getTimestamp() < time();
    }
}
