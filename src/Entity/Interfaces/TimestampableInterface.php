<?php

namespace App\Entity\Interfaces;

use DateTime;
use DateTimeImmutable;

interface TimestampableInterface
{
    public function setCreatedAt(): static;

    public function getCreatedAt(): DateTimeImmutable;

    public function setUpdatedAt(): static;

    public function getUpdatedAt(): ?DateTime;

    public function setDeletedAt(DateTimeImmutable $deletedAt): static;

    public function getDeletedAt(): ?DateTimeImmutable;

    public function isRemoved(): bool;
}