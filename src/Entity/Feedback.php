<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'feedback')]
#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class,
        cascade: ['persist'],
        inversedBy: 'feedback')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(targetEntity: ServiceType::class,
        cascade: ['persist'],
        inversedBy: 'feedback')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ServiceType $service_type = null;

    #[ORM\Column]
    private ?bool $phone_call = null;

    #[ORM\Column]
    private ?bool $telegram = null;

    #[ORM\Column]
    private ?bool $whatsapp = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $done_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
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

    public function getServiceType(): ?ServiceType
    {
        return $this->service_type;
    }

    public function setServiceType(?ServiceType $service_type): static
    {
        $this->service_type = $service_type;

        return $this;
    }

    public function isPhoneCall(): ?bool
    {
        return $this->phone_call;
    }

    public function setPhoneCall(bool $phone_call): static
    {
        $this->phone_call = $phone_call;

        return $this;
    }

    public function isTelegram(): ?bool
    {
        return $this->telegram;
    }

    public function setTelegram(bool $telegram): static
    {
        $this->telegram = $telegram;

        return $this;
    }

    public function isWhatsapp(): ?bool
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(bool $whatsapp): static
    {
        $this->whatsapp = $whatsapp;

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
