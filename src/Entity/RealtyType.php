<?php

namespace App\Entity;

use App\Repository\RealtyTypeRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


///
/// Тип объекта
///

#[ORM\Table(name: 'property_types')]
#[ORM\Entity(repositoryClass: RealtyTypeRepository::class)]
class RealtyType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(nullable: false)]
    private ?int $id;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Choice(
        choices: ['flat', 'house', 'commerce']
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
