<?php

namespace App\ValueObject\Project;

use App\ValueObject\Interfaces\ValueObjectInterface;
use App\ValueObject\Traits\EqualsTrait;
use InvalidArgumentException;

final class Category implements ValueObjectInterface
{
    use EqualsTrait;

    private const ALLOWED_CATEGORIES
        = [
            self::SOFTWARE_DEVELOPMENT,
            self::DEVOPS,
            self::FREELANCE,
            self::HOME_PROJECTS,
            self::EDUCATION,
            self::OTHER
        ];

    private const SOFTWARE_DEVELOPMENT = 'software_development';
    private const DEVOPS               = 'devops';
    private const FREELANCE            = 'freelance';
    private const HOME_PROJECTS        = 'home_projects';
    private const EDUCATION            = 'education';
    private const OTHER                = 'other';

    public function __construct(private readonly string $value)
    {
        if (!in_array($value, self::ALLOWED_CATEGORIES, true)) {
            throw new InvalidArgumentException(sprintf('Invalid status value "%s".', $value));
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function softwareDevelopment(): self
    {
        return new self(self::SOFTWARE_DEVELOPMENT);
    }

    public static function devops(): self
    {
        return new self(self::DEVOPS);
    }

    public static function freelance(): self
    {
        return new self(self::FREELANCE);
    }

    public static function homeProjects(): self
    {
        return new self(self::HOME_PROJECTS);
    }

    public static function education(): self
    {
        return new self(self::EDUCATION);
    }

    public static function other(): self
    {
        return new self(self::OTHER);
    }
}