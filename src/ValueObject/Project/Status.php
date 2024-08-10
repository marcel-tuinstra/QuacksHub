<?php

namespace App\ValueObject\Project;

use App\ValueObject\Interfaces\ValueObjectInterface;
use App\ValueObject\Traits\EqualsTrait;
use InvalidArgumentException;

final class Status implements ValueObjectInterface
{
    use EqualsTrait;

    private const ALLOWED_STATUSES
        = [
            self::STATUS_NOT_STARTED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_IN_TESTING,
            self::STATUS_COMPLETED,
            self::STATUS_HALTED,
        ];

    private const STATUS_NOT_STARTED = 'not_started';
    private const STATUS_IN_PROGRESS = 'in_progress';
    private const STATUS_IN_TESTING = 'in_testing';
    private const STATUS_COMPLETED   = 'completed';
    private const STATUS_HALTED      = 'halted';

    public function __construct(private readonly string $value)
    {
        if (!in_array($value, self::ALLOWED_STATUSES, true)) {
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

    public static function notStarted(): self
    {
        return new self(self::STATUS_NOT_STARTED);
    }

    public static function inProgress(): self
    {
        return new self(self::STATUS_IN_PROGRESS);
    }
    public static function inTesting(): self
    {
        return new self(self::STATUS_IN_TESTING);
    }

    public static function completed(): self
    {
        return new self(self::STATUS_COMPLETED);
    }

    public static function halted(): self
    {
        return new self(self::STATUS_HALTED);
    }
}