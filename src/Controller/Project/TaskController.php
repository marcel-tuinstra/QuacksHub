<?php

namespace App\Controller\Project;

use App\DTO\Project\TaskDTO;
use App\Entity\Project;
use App\Entity\Project\Task;
use App\Service\Project\TaskService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/project/task', name: 'app_project_task_')]
class TaskController extends AbstractController
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly UserService $userService
    )
    {
        // noop
    }

    #[Route('/{id}', name: 'create', methods: ['POST'])]
    #[IsGranted(attribute: 'project_update', subject: 'project')]
    public function create(Request $request, Project $project): Response
    {
        $currentUser = $this->userService->getByToken($request->headers->get('Authorization'));
        $task        = $this->taskService->create($project, $currentUser);

        return $this->json($task);
    }

    #[Route('/{id}', name: 'read_all', methods: ['GET', 'OPTIONS'])]
    public function readAll(Request $request, Project $project): Response
    {
        $currentUser = $this->userService->getByToken($request->headers->get('Authorization'));

        return $this->json($this->taskService->getTasksByProject($project)->sortedByCreated());
    }

    #[Route('/{id}', name: 'read', methods: ['GET'])]
    public function read(Task $task): Response
    {
        return $this->json($task);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    #[IsGranted(attribute: 'task_update', subject: 'task')]
    public function update(Request $request, ValidatorInterface $validator, Task $task): Response
    {
        // Decode the JSON content and create our Project DTO
        $taskDTO = TaskDTO::fromRequest($request);

        // Validate the DTO
        $violations = $validator->validate($taskDTO);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        // Pass the DTO to the service
        $this->taskService->update($task, $taskDTO);

        return $this->json($task);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[IsGranted(attribute: 'task_delete', subject: 'task')]
    public function delete(Task $task): Response
    {
        $this->taskService->delete($task);

        return $this->json([]);
    }
}
