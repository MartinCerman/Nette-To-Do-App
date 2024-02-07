<?php

namespace App\Models;

use Nette\Bridges\ApplicationLatte\Template;

class TasksTemplate extends Template
{
    public Task $task;
}