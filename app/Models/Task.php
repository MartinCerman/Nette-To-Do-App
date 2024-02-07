<?php

namespace App\Models;

class Task
{
    public int $id;
    public string $name;
    public ?string $description;
    public bool $isCompleted;
    public \DateTime $insertionDate;
}