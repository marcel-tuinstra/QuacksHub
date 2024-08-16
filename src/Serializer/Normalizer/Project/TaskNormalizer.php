<?php

namespace App\Serializer\Normalizer\Project;

use App\Entity\Project\Task;
use DateTimeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TaskNormalizer implements NormalizerInterface
{
    /**
     * @param Task $object
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        return [
            'id'          => $object->getId(),
            'description' => $object->getDescription(),
            'completedAt' => $object->getCompletedAt()?->format('Y-m-d'),
            'meta'        => [
                'createdAt' => $object->getCreatedAt()?->format(DateTimeInterface::ATOM),
                'updatedAt' => $object->getUpdatedAt()?->format(DateTimeInterface::ATOM),
                'deletedAt' => $object->getDeletedAt()?->format(DateTimeInterface::ATOM),
                'isDeleted' => $object->isDeleted()
            ],
            'counters'    => []
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Task;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Task::class => true];
    }
}
