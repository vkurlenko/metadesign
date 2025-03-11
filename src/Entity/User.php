<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'users')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $chat_id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $first_name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 10, max: 10)]
    #[Assert\Regex(
        '/^\d+$/',
        message: 'Номер телефона должен содержать только цифры'
    )]
    private ?string $phone_number = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getChatId(): ?int
    {
        return $this->chat_id;
    }

    public function setChatId(?int $chat_id): static
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): static
    {
        $this->phone_number = $phone_number;

        return $this;
    }
}
