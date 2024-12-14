<?php

namespace App\ValueObject\Investment;

use App\ValueObject\Interfaces\ValueObjectInterface;
use App\ValueObject\Traits\EqualsTrait;
use InvalidArgumentException;

final class Category implements ValueObjectInterface
{
    use EqualsTrait;

    private const ALLOWED_CATEGORIES
        = [
            self::STOCKS,
            self::OBLIGATIONS,
            self::FUNDS,
            self::REAL_ESTATE,
            self::CRYPTOCURRENCY,
            self::PEER_TO_PEER_LENDING,
            self::SAVINGS_ACCOUNT,
            self::PRECIOUS_METALS,
            self::ART,
            self::DERIVATIVES,
            self::VENTURE_CAPITAL,
            self::PRIVATE_EQUITY,
        ];

    private const STOCKS               = 'stocks'; // Aandelen
    private const OBLIGATIONS          = 'obligations'; // Obligaties
    private const FUNDS                = 'funds'; // Beleggingsfondsen
    private const REAL_ESTATE          = 'real_estate'; // Vastgoed
    private const CRYPTOCURRENCY       = 'cryptocurrency'; // Cryptovaluta
    private const PEER_TO_PEER_LENDING = 'peer_to_peer_lending'; // P2P-leningen
    private const SAVINGS_ACCOUNT      = 'savings_account'; // Spaarrekeningen
    private const PRECIOUS_METALS      = 'precious_metals'; // Edelmetalen
    private const ART                  = 'art'; // Kunst
    private const DERIVATIVES          = 'derivatives'; // Derivaten
    private const VENTURE_CAPITAL      = 'venture_capital'; // Risicokapitaal
    private const PRIVATE_EQUITY       = 'private_equity'; // Private equity

    public function __construct(private readonly string $value)
    {
        if (!in_array($value, self::ALLOWED_CATEGORIES, true)) {
            throw new InvalidArgumentException(sprintf('Invalid category value "%s".', $value));
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

    public static function stocks(): self
    {
        return new self(self::STOCKS);
    }

    public static function obligations(): self
    {
        return new self(self::OBLIGATIONS);
    }

    public static function funds(): self
    {
        return new self(self::FUNDS);
    }

    public static function realEstate(): self
    {
        return new self(self::REAL_ESTATE);
    }

    public static function cryptocurrency(): self
    {
        return new self(self::CRYPTOCURRENCY);
    }

    public static function peerToPeerLending(): self
    {
        return new self(self::PEER_TO_PEER_LENDING);
    }

    public static function savingsAccount(): self
    {
        return new self(self::SAVINGS_ACCOUNT);
    }

    public static function preciousMetals(): self
    {
        return new self(self::PRECIOUS_METALS);
    }

    public static function art(): self
    {
        return new self(self::ART);
    }

    public static function derivatives(): self
    {
        return new self(self::DERIVATIVES);
    }

    public static function ventureCapital(): self
    {
        return new self(self::VENTURE_CAPITAL);
    }

    public static function privateEquity(): self
    {
        return new self(self::PRIVATE_EQUITY);
    }
}