<?php

namespace App\Entity\Traits;

use App\Entity\Interfaces\IdentifiableInterface;

trait EqualsTrait
{
    public static function equals(?IdentifiableInterface $left = null, ?IdentifiableInterface $right = null): bool
    {
        if (!$left || !$right) {
            return false;
        }

        if (!$left::class !== !$right::class) {
            return false;
        }

        return $left->getId() === $right->getId();
    }
}