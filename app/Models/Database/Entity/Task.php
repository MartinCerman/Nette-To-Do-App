<?php

declare(strict_types=1);

namespace App\Models\Database\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tasks')]
class Task
{
    #[ORM\Id]
    #[ORM\Column(name: 'id')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::TEXT)]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\Column(name: 'isCompleted', type: Types::BOOLEAN)]
    private bool $isCompleted;

    #[ORM\Column(name: 'insertionDate', type: Types::INTEGER)]
    private int $insertionDate;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): void
    {
        $this->isCompleted = $isCompleted;
    }

    public function getInsertionDate(): DateTime
    {
        return (new DateTime())->setTimestamp($this->insertionDate);
    }
}