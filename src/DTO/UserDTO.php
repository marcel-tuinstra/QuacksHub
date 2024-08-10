<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;

class UserDTO
{
    const OPTION_NICKNAME = 'nickname';

    #[Length(min: 0, max: 64)]
    public ?string $nickname = null;

    public function __construct(array $data)
    {
        $this->nickname = !empty($data[self::OPTION_NICKNAME]) ? $data[self::OPTION_NICKNAME] : null;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(json_decode($request->getContent(), true));
    }
}