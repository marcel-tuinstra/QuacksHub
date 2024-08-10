<?php

namespace App\Serializer\Normalizer;

use App\Entity\Project;
use DateTimeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ProjectNormalizer implements NormalizerInterface
{
    /**
     * @param Project $object
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        return [
            'id'          => $object->getId(),
            'title'       => $object->getTitle(),
            'description' => $object->getDescription(),
            'category'    => $object->getCategory()->value(),
            'status'      => $object->getStatus()->value(),
            'dueAt'       => $object->getDueAt()?->format('Y-m-d'),
            'percentage'  => mt_rand(0, 100),
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
        return $data instanceof Project;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Project::class => true];
    }
}
