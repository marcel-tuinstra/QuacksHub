<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use DateTime;

final class TokenListener
{
    public function __construct(private readonly UserService $userService)
    {
        // noop
    }

    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (str_contains($request->getPathInfo(), 'login') || !$request->headers->has('Authorization')) {
            return;
        }

        $token              = $request->headers->get('Authorization');
        $validationResponse = $this->validateToken($token);

        if (!$validationResponse instanceof User) {
            $event->setResponse(new JsonResponse(['error' => $validationResponse], JsonResponse::HTTP_UNAUTHORIZED));
        }
    }

    public function validateToken(string $token): User|string
    {
        $user = $this->userService->getByToken($token);

        if (!$user instanceof User) {
            return 'Unknown Token';
        }

        if ($user->isTokenExpired()) {
            return 'Expired Token';
        }

        return $user;
    }

}
