<?php

namespace App\Service;

use App\Collection\ProjectCollection;
use App\Entity\User;
use App\Repository\ProjectRepository;

class ProjectService
{
    public function __construct(private readonly ProjectRepository $projectRepository)
    {
        // noop
    }

    public function getAllProjects(): ProjectCollection
    {
        return new ProjectCollection($this->projectRepository->findAll());
    }

    public function getAllProjectsByUser(User $user): ProjectCollection
    {
        return new ProjectCollection($this->projectRepository->findAllByUser($user));
    }
}