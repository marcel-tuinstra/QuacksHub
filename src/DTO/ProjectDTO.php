<?php

namespace App\DTO;

use App\ValueObject\Project\Category;
use App\ValueObject\Project\Status;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class ProjectDTO
{
    const OPTION_TITLE       = 'title';
    const OPTION_CATEGORY    = 'category';
    const OPTION_STATUS      = 'status';
    const OPTION_DUE_AT      = 'dueAt';
    const OPTION_DESCRIPTION = 'description';

    #[NotNull]
    #[Length(min: 3, max: 64)]
    public ?string $title = null;

    #[NotNull]
    public ?Category $category = null;

    #[NotNull]
    public ?Status $status = null;

    public ?DateTime $dueAt = null;

    #[Length(min: 0, max: 1024, maxMessage: "Description cannot be longer than {{ limit }} characters")]
    public ?string $description = null;

    public function __construct(array $data)
    {
        $this->title       = !empty($data[self::OPTION_TITLE]) ? $data[self::OPTION_TITLE] : null;
        $this->category    = !empty($data[self::OPTION_CATEGORY]) ? new Category($data[self::OPTION_CATEGORY]) : null;
        $this->status      = !empty($data[self::OPTION_STATUS]) ? new Status($data[self::OPTION_STATUS]) : null;
        $this->dueAt       = !empty($data[self::OPTION_DUE_AT]) ? new DateTime($data[self::OPTION_DUE_AT]) : null;
        $this->description = !empty($data[self::OPTION_DESCRIPTION]) ? $data[self::OPTION_DESCRIPTION] : null;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(json_decode($request->getContent(), true));
    }
}