<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/user', name: 'app_user_')]
class UserController extends AbstractController
{
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        // Logic to create a new user
    }

    #[Route('', name: 'read_all', methods: ['GET', 'OPTIONS'])]
    public function readAll(UserService $userService): Response
    {
        return $this->json($userService->getAll());
    }

    #[Route('/{id}', name: 'read', methods: ['GET'])]
    public function read(User $user): Response
    {
        return $this->json($user);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, User $user): Response
    {
        // Logic to update an existing user
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(User $user): Response
    {
        // Logic to delete a user
    }
}
