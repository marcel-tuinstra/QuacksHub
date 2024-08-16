<?php

namespace App\Security\Voter\Project;

use App\Entity\Project;
use App\Entity\Project\Task;
use App\Security\Voter\UserTokenSupportedVoterTrait;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TaskVoter extends Voter
{
    use UserTokenSupportedVoterTrait;

    public const UPDATE = 'task_update';
    public const DELETE = 'task_delete';

    public function __construct(RequestStack $requestStack, UserService $userService)
    {
        $this->__initializeUserTokenSupportedVoterTrait($requestStack, $userService);
    }

    /**
     * @return string[]
     */
    private function supportedAttributes(): array
    {
        return [self::UPDATE];
    }

    /**
     * @return string[]
     */
    private function supportedAttributesForSubject(): array
    {
        return [self::UPDATE, self::DELETE];
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (in_array($attribute, array_diff($this->supportedAttributes(), $this->supportedAttributesForSubject()))) {
            return true;
        }

        return in_array($attribute, $this->supportedAttributesForSubject()) && $subject instanceof Task;
    }

    /**
     * @param Project|null $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return match ($attribute) {
            self::UPDATE, self::DELETE => $subject->isOwner($this->getCurrentUser()),
            default => false,
        };
    }
}
