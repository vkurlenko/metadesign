<?php

namespace App\Entity;

class Project
{
    private ?int $id = null;

    private ?string $identifier = null;

    private ?string $name = null;

    private ?string $description = null;

    private ?string $city = null;
    private ?string $text = null;
    private ?array $files = null;

    private ?array $items = null;

    private ?string $dir = null;
    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $square): void
    {
        $this->text = $square;
    }
    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }
    public function getDir(): ?string
    {
        return $this->dir;
    }

    public function setDir(?string $dir): void
    {
        $this->dir = $dir;
    }


    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(?array $items): void
    {
        $this->items = $items;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): static
    {
        $this->identifier = $identifier;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFiles(): ?array
    {
        return $this->files;
    }

    public function setFiles(?array $files): static
    {
        $this->files = $files;

        return $this;
    }
}
