<?php

declare(strict_types=1);

namespace App\Components;

use App\Models\TasksTemplate;
use Nette\Application\UI\Control;

/** @property-read TasksTemplate $template */
class TasksTableControl extends Control
{
    public function render(array $tasks): void
    {
        $this->template->tasks = $tasks;
        $this->template->render(__DIR__ . '/TasksTableTemplate.latte');
    }
}