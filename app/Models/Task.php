<?php

namespace App\Models;

class Task
{
    public int $taskId;
    public string $name;
    public ?string $description;
    public bool $isCompleted;
    public \DateTime $insertionDate;
}