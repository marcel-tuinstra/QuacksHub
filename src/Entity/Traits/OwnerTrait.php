<?php

namespace App\Entity\Traits;

use App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

trait OwnerTrait
{
    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner;

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function isOwner(User $user): bool
    {
        return User::equals($this->owner, $user);
    }
}