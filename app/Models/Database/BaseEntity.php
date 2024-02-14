<?php

declare(strict_types=1);

namespace App\Models\Database;

abstract class BaseEntity
{
    abstract public function toArray(): array;
}