<?php

declare(strict_types=1);

namespace App\Components;

use App\Models\TasksTemplate;
use App\Models\UploadsRepository;
use Nette\Application\UI\Control;

/** @property-read TasksTemplate $template */
class TasksTableControl extends Control
{
    public function __construct(private UploadsRepository $uploadsRepository)
    {
    }

    public function render(array $tasks): void
    {
        $this->template->tasks = $tasks;
        $this->template->render(__DIR__ . '/TasksTableTemplate.latte');
    }

    public function getTaskFile($taskId) : ?string
    {
        $file = $this->uploadsRepository->findFile((string)$taskId);

        if($file){
            return $file->getFilename();
        }

        return null;
    }
}