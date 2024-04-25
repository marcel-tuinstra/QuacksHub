<?php

namespace App\Collection;

use App\Entity\Project;
use Doctrine\Common\Collections\Collection;

/**
 * @implements Collection<int, Project>
 */
class ProjectCollection extends ObjectCollection implements Collection
{
    protected ?string $supportedClass = Project::class;

    // Filters
    //////////////////////////////
}