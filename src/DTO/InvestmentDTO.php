<?php

namespace App\DTO;

use App\ValueObject\Investment\Category;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Url;

class InvestmentDTO
{
    // Required Fields
    const OPTION_NAME       = 'name';
    const OPTION_CATEGORY   = 'category';
    const OPTION_AMOUNT     = 'amount';
    const OPTION_RATE       = 'rate';
    const OPTION_START_DATE = 'description';

    // Optional Fields
    const OPTION_COMPANY  = 'company';
    const OPTION_URL      = 'url';
    const OPTION_END_DATE = 'end_date';

    #[NotNull]
    #[Length(min: 3, max: 64)]
    public ?string $name = null;

    #[NotNull]
    public ?Category $category = null;

    #[NotNull]
    public ?float $amount = null;

    #[NotNull]
    public ?float $rate = null;

    #[NotNull]
    public ?DateTime $startDate = null;

    #[Length(min: 3, max: 64)]
    public ?string $company = null;

    #[Url]
    #[Length(min: 3, max: 255)]
    public ?string $url = null;

    public ?DateTime $endDate = null;

    public function __construct(array $data)
    {
        $this->name      = !empty($data[self::OPTION_NAME]) ? $data[self::OPTION_NAME] : null;
        $this->category  = !empty($data[self::OPTION_CATEGORY]) ? new Category($data[self::OPTION_CATEGORY]) : null;
        $this->amount    = !empty($data[self::OPTION_AMOUNT]) ? $data[self::OPTION_AMOUNT] : null;
        $this->rate      = !empty($data[self::OPTION_RATE]) ? $data[self::OPTION_RATE] : null;
        $this->startDate = !empty($data[self::OPTION_START_DATE]) ? new DateTime($data[self::OPTION_START_DATE]) : null;
        $this->company   = !empty($data[self::OPTION_COMPANY]) ? $data[self::OPTION_COMPANY] : null;
        $this->url       = !empty($data[self::OPTION_URL]) ? $data[self::OPTION_URL] : null;
        $this->endDate   = !empty($data[self::OPTION_END_DATE]) ? new DateTime($data[self::OPTION_END_DATE]) : null;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(json_decode($request->getContent(), true));
    }
}