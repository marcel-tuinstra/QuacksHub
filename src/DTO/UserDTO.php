<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class UserDTO
{
    #[NotNull(message: "Nickname cannot be null.")]
    #[Length(min: 5, max: 64, maxMessage: "Nickname cannot be longer than {{ limit }} characters")]
    public ?string $nickname = null;

    public function __construct(array $data)
    {
        $this->nickname = !empty($data['nickname']) ? $data['nickname'] : null;
    }
}