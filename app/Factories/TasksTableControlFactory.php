<?php

declare(strict_types=1);

namespace App\Factories;

use App\Components\TasksTable\TasksTableControl;

interface TasksTableControlFactory
{
    function create(): TasksTableControl;
}