<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectVoter extends Voter
{
    use UserTokenSupportedVoterTrait;

    public const CREATE = 'project_create';
    public const UPDATE = 'project_update';
    public const DELETE = 'project_delete';

    public function __construct(RequestStack $requestStack, UserService $userService)
    {
        $this->__initializeUserTokenSupportedVoterTrait($requestStack, $userService);
    }

    /**
     * @return string[]
     */
    private function supportedAttributes(): array
    {
        return [self::CREATE, self::UPDATE];
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

        return in_array($attribute, $this->supportedAttributesForSubject()) && $subject instanceof Project;
    }

    /**
     * @param Project|null $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return match ($attribute) {
            self::CREATE => true,
            self::UPDATE, self::DELETE => $subject->isOwner($this->getCurrentUser()),
            default => false,
        };
    }
}
