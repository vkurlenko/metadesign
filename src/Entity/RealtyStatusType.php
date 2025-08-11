<?php

namespace App\Entity;

use App\Repository\RealtyStatusTypeRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

////
///
/// Новостройка или вторичка
///
///
#[ORM\Table(name: 'realty_status_types')]
#[ORM\Entity(repositoryClass: RealtyStatusTypeRepository::class)]
class RealtyStatusType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(nullable: false)]
    private ?int $id;

    #[ORM\Column(length: 255, unique: true)]
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
