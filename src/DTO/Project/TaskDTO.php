<?php

namespace App\DTO\Project;

use App\Entity\Project;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class TaskDTO
{
    const OPTION_DESCRIPTION  = 'description';
    const OPTION_COMPLETED_AT = 'completedAt';

    public ?DateTime $completedAt = null;

    #[NotNull]
    #[Length(min: 0, max: 1024, maxMessage: "Description cannot be longer than {{ limit }} characters")]
    public ?string $description = null;

    public function __construct(array $data)
    {
        $this->completedAt = !empty($data[self::OPTION_COMPLETED_AT]) ? new DateTime($data[self::OPTION_COMPLETED_AT]) : null;
        $this->description = !empty($data[self::OPTION_DESCRIPTION]) ? $data[self::OPTION_DESCRIPTION] : null;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(json_decode($request->getContent(), true));
    }
}