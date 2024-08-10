<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\RequestStack;

trait UserTokenSupportedVoterTrait
{
    private User $currentUser;

    protected function __initializeUserTokenSupportedVoterTrait(
        RequestStack $requestStack,
        UserService  $userService
    ): void
    {
        // Extract the current request from the request stack
        $token             = $requestStack->getCurrentRequest()->headers->get('Authorization');
        $this->currentUser = $userService->getByToken($token);
    }

    protected function getCurrentUser(): User
    {
        return $this->currentUser;
    }
}