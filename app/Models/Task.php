<?php

namespace App\Models;

/**
 * Task model represents a record in the tasks table in the database.
 */
class Task
{
    public int $id;
    public string $name;
    public ?string $description;
    public bool $isCompleted;
    public \DateTime $insertionDate;
}