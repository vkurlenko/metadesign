<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'orders')]
#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(nullable: false)] // TODO если !nullable, то private int $id, а не ?int $id;
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class,
        cascade: ['persist'],
        inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(targetEntity: RealtyType::class,
        cascade: ['persist'],
        inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RealtyType $realty_type = null;

    #[ORM\Column]
    #[Assert\Type('float')]
    #[Assert\NotNull]
    #[Assert\Positive]
    #[Assert\LessThanOrEqual(3000)]
    private ?float $square = null;

    #[ORM\ManyToOne(targetEntity: RealtyStatusType::class,
        cascade: ['persist'],
        inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RealtyStatusType $realty_status_type = null;

    #[ORM\ManyToOne(targetEntity: RepairType::class,
        cascade: ['persist'],
        inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RepairType $repairType = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $cost = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $done_at = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getRealtyType(): ?RealtyType
    {
        return $this->realty_type;
    }

    public function setRealtyType(?RealtyType $realty_type): static
    {
        $this->realty_type = $realty_type;

        return $this;
    }

    public function getSquare(): ?float
    {
        return $this->square;
    }

    public function setSquare(float $square): static
    {
        $this->square = $square;

        return $this;
    }

    public function getRealtyStatusType(): ?RealtyStatusType
    {
        return $this->realty_status_type;
    }

    public function setRealtyStatusType(?RealtyStatusType $realty_status_type): static
    {
        $this->realty_status_type = $realty_status_type;

        return $this;
    }

    public function getRepairType(): ?RepairType
    {
        return $this->repairType;
    }

    public function setRepairType(?RepairType $repairType): static
    {
        $this->repairType = $repairType;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(string $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDoneAt(): ?\DateTimeInterface
    {
        return $this->done_at;
    }

    public function setDoneAt(?\DateTimeInterface $done_at): static
    {
        $this->done_at = $done_at;

        return $this;
    }
}
