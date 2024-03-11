<?php

namespace App\Models;
use App\Models\Database\Entity\Task;
use Nette\Bridges\ApplicationLatte\Template;

class TaskTemplate extends Template
{
    public Task $task;

    /** @var Task[] */
    public array $tasks;
}