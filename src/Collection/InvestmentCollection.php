<?php

namespace App\Collection;

use App\Entity\Investment;
use App\Entity\Project;
use Doctrine\Common\Collections\Collection;

/**
 * @implements Collection<int, Project>
 */
class InvestmentCollection extends ObjectCollection implements Collection
{
    protected ?string $supportedClass = Investment::class;

    // Filters
    //////////////////////////////
}