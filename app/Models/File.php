<?php

declare(strict_types=1);

namespace App\Models;

/**
 * File model represents a record in the files table in the database.
 */
class File
{
    public int $id;
    public int $taskId;
    public string $name;
}