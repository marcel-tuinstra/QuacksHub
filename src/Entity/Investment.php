<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\EqualsTrait;
use App\Entity\Traits\OwnerTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\InvestmentRepository;
use App\ValueObject\Investment\Category;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: InvestmentRepository::class)]
#[ORM\Table(name: '`investment`')]
class Investment
{
    use EqualsTrait;
    use OwnerTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $name;

    #[ORM\Column(type: 'investment:category')]
    private ?Category $category;

    #[ORM\Column]
    private ?float $amount;

    #[ORM\Column]
    private ?float $rate;

    #[ORM\Column(length: 64)]
    private ?string $company;

    #[ORM\Column(length: 255)]
    private ?string $url;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private DateTimeInterface $startDate;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $endDate = null;

    public function __construct(User $owner, string $name, Category $category, float $amount, float $rate, DateTimeInterface $startDate)
    {
        $this->owner     = $owner;
        $this->name      = $name;
        $this->category  = $category;
        $this->amount    = $amount;
        $this->rate      = $rate;
        $this->startDate = $startDate;
    }

    // Getters
    //////////////////////////////

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getStartDate(): DateTimeInterface
    {
        return $this->startDate;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    // Setters
    //////////////////////////////

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setStartDate(DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function setEndDate(?DateTimeInterface $endDate = null): void
    {
        $this->endDate = $endDate;
    }

    // Derived Methods
    //////////////////////////////

    public function isActive(): bool
    {
        $now = new DateTimeImmutable();

        return $this->startDate <= $now && $this->endDate >= $now;
    }

    /**
     * Calculate total expected return.
     */
    public function getExpectedTotalReturn(): float
    {
        $years = $this->startDate->diff($this->endDate)->y;

        return $this->amount * pow((1 + $this->rate / 100), $years);
    }

    /**
     * Calculate annualized return.
     */
    public function getExpectedAnnualReturn(): float
    {
        return $this->amount * ($this->rate / 100);
    }
}
