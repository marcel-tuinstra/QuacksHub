<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use App\Service\Project\TaskService;
use App\Service\ProjectService;
use DateTimeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    public function __construct(
        private readonly ProjectService $projectService,
        private readonly TaskService    $taskService,
    )
    {
        // noop
    }

    /**
     * @param User $object
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        return [
            'id'       => $object->getId(),
            'email'    => $object->getEmail(),
            'nickname' => $object->getNickname(),
            'name'     => $object->getNickname() ?? $object->getEmail(),
            'meta'     => [
                'createdAt' => $object->getCreatedAt()->format(DateTimeInterface::ATOM),
            ],
            'counters' => [
                'projects' => [
                    'total'  => $this->projectService->getProjectsCountByOwner($object),
                    'active' => $this->projectService->getActiveProjectsCountByOwner($object),
                ],
                'tasks'    => [
                    'total'      => $this->taskService->getTasksCountByOwner($object),
                    'complete'   => $this->taskService->getCompletedTasksCountByOwner($object),
                    'incomplete' => $this->taskService->getNotCompletedTasksCountByOwner($object),
                ]
            ]
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [User::class => true];
    }
}
