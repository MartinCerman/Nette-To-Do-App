<?php

declare(strict_types=1);

namespace App\Models\Database\Entity;

use App\Models\Database\BaseEntity;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tasks')]
class Task extends BaseEntity
{
    #[ORM\Id]
    #[ORM\Column(name: 'id')]
    #[ORM\GeneratedValue]
    protected int $id;

    #[ORM\Column(type: Types::TEXT)]
    protected string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $description;

    #[ORM\Column(name: 'isCompleted', type: Types::BOOLEAN)]
    protected bool $isCompleted;

    #[ORM\Column(name: 'insertionDate', type: Types::INTEGER)]
    protected int $insertionDate;

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

    public function toArray(): array
    {
        return [
          'id' => $this->getId(),
          'name' => $this->getName(),
          'description' => $this->getDescription(),
          'isCompleted' => $this->isCompleted(),
          'insertionDate' => $this->getInsertionDate()
        ];
    }
}