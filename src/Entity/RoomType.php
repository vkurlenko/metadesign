<?php

namespace App\Entity;

use App\Repository\RoomTypeRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

////
///
/// Новостройка или вторичка
///
///
#[ORM\Table(name: 'room_types')]
#[ORM\Entity(repositoryClass: RoomTypeRepository::class)]
class RoomType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Choice(
        choices: ['new', 'secondary']
    )]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
