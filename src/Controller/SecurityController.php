<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\VarDumper\VarDumper;

#[Route('/api', name: 'app_security_')]
class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['POST', 'OPTIONS'])]
    public function login(Request $request, UserService $userService): JsonResponse
    {
        // Respond with a meaningful message if the method is OPTIONS or anything else
        if ($request->getMethod() === 'OPTIONS') {
            return new JsonResponse(
                ['message' => 'OK'],
                Response::HTTP_NO_CONTENT,
                [
                    'Access-Control-Allow-Origin' => '*',  // Adjust based on your needs
                    'Access-Control-Allow-Methods' => 'POST, OPTIONS',
                    'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
                ]
            );
        }

        $data  = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $sub   = $data['sub'] ?? null;
        if ($email !== null || $sub !== null) {
            if (!$email || !$sub) {
                return $this->json(['error' => 'Login failed, invalid credentials provided.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $user = $userService->getByEmailAndSub($email, $sub);
            if (!$user) {
                $user = $userService->createFromAuth0($email, $sub);
            }

            $userService->generateToken($user);
        } else {
            $token = $request->headers->get('Authorization');
            $user  = $userService->getByToken($token);

            if ($user->isTokenExpired()) {
                return $this->json(['error' => 'Login failed, token expired.'], JsonResponse::HTTP_BAD_REQUEST);
            }

            if ($user->isTokenAboutToExpire()) {
                $userService->generateToken($user);
            }
        }

        return $this->json(['token' => $user->getToken(), 'user' => $user]);
    }
}
