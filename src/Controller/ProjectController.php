<?php

namespace App\Controller;

use App\DTO\ProjectDTO;
use App\Entity\Project;
use App\Service\ProjectService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/project', name: 'app_project_')]
class ProjectController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly UserService    $userService
    )
    {
        // noop
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[IsGranted(attribute: 'project_create')]
    public function create(Request $request, ValidatorInterface $validator): Response
    {
        // Decode the JSON content and create our Project DTO
        $projectDTO = ProjectDTO::fromRequest($request);

        // Validate the DTO
        $violations = $validator->validate($projectDTO);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $currentUser = $this->userService->getByToken($request->headers->get('Authorization'));
        $project     = $this->projectService->create($projectDTO, $currentUser);

        return $this->json($project);
    }

    #[Route('', name: 'read_all', methods: ['GET', 'OPTIONS'])]
    public function readAll(Request $request): Response
    {
        $currentUser    = $this->userService->getByToken($request->headers->get('Authorization'));
        $excludeDeleted = $request->query->getBoolean('excludeDeleted', true);

        return $this->json($this->projectService->getAllProjectsByOwner($currentUser, $excludeDeleted));
    }

    #[Route('/{id}', name: 'read', methods: ['GET'])]
    public function read(Project $project): Response
    {
        return $this->json($project);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[IsGranted(attribute: 'project_update', subject: 'project')]
    public function update(Request $request, ValidatorInterface $validator, Project $project): Response
    {
        // Decode the JSON content and create our Project DTO
        $projectDTO = ProjectDTO::fromRequest($request);

        // Validate the DTO
        $violations = $validator->validate($projectDTO);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        // Pass the DTO to the service
        $this->projectService->update($project, $projectDTO);

        return $this->json($project);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[IsGranted(attribute: 'project_delete', subject: 'project')]
    public function delete(Project $project): Response
    {
        $this->projectService->delete($project);

        return $this->json([]);
    }
}
