<?php

declare(strict_types=1);

namespace App\Models;

enum TaskStatusFilter: int
{
    case Active = 0;
    case Completed = 1;
    case All = 2;
}
