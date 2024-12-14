<?php

namespace App\Serializer\Normalizer;

use App\Entity\Investment;
use DateTimeInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class InvestmentNormalizer implements NormalizerInterface
{
    /**
     * @param Investment $object
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        return [
            'id'                   => $object->getId(),
            'name'                 => $object->getName(),
            'company'              => $object->getCompany(),
            'url'                  => $object->getUrl(),
            'amount'               => $object->getAmount(),
            'rate'                 => $object->getRate(),
            'category'             => $object->getCategory()->value(),
            'startDate'            => $object->getStartDate()?->format('Y-m-d'),
            'endDate'              => $object->getEndDate()?->format('Y-m-d'),
            'active'               => $object->isActive(),
            'expectedTotalReturn'  => $object->getExpectedTotalReturn(),
            'expectedAnnualReturn' => $object->getExpectedAnnualReturn(),
            'meta'                 => [
                'createdAt' => $object->getCreatedAt()?->format(DateTimeInterface::ATOM),
                'updatedAt' => $object->getUpdatedAt()?->format(DateTimeInterface::ATOM),
                'deletedAt' => $object->getDeletedAt()?->format(DateTimeInterface::ATOM),
                'isDeleted' => $object->isDeleted()
            ],
            'counters'             => []
        ];
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Investment;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Investment::class => true];
    }
}
