<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    /**
     * @param User $object
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        return [
            'id'    => $object->getId(),
            'email' => $object->getEmail()
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
