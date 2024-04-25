<?php

namespace App\Collection;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;

/**
 * @implements Collection<int, User>
 */
class UserCollection extends ObjectCollection implements Collection
{
    protected ?string $supportedClass = User::class;

    // Filters
    //////////////////////////////
}