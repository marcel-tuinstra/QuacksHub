<?php

namespace App\Controller;

use App\Collection\ProjectCollection;
use App\Entity\Project;
use App\Service\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/project', name: 'app_project_')]
class ProjectController extends AbstractController
{
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        // Logic to create a new user
    }

    #[Route('', name: 'read_all', methods: ['GET'])]
    public function readAll(ProjectService $projectService): Response
    {
        $this->json($projectService->getAllProjects());
    }

    #[Route('/{id}', name: 'read', methods: ['GET'])]
    public function read(Project $project): Response
    {
        return $this->json($project);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, Project $project): Response
    {
        // Logic to update an existing user
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Project $project): Response
    {
        // Logic to delete a user
    }
}
