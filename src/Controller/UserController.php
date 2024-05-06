<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/user', name: 'app_user_')]
class UserController extends AbstractController
{
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        return $this->json([]);
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
    public function update(Request $request, ValidatorInterface $validator, UserService $userService, User $user): Response
    {
        // Decode the JSON content and create our User DTO
        $userDTO = new UserDTO(json_decode($request->getContent(), true));

        // Validate the DTO
        $violations = $validator->validate($userDTO);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        // Pass the DTO to the service
        $userService->update($user, $userDTO);

        // Return the updated user data
        return $this->json($user);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(User $user): Response
    {
        return $this->json([]);
    }
}
