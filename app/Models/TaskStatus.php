<?php

declare(strict_types=1);

namespace App\Models;

enum TaskStatus: int
{
    case Active = 0;
    case Completed = 1;
}
