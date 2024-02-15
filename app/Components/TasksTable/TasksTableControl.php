<?php

declare(strict_types=1);

namespace App\Components\TasksTable;

use App\Models\TasksTemplate;
use App\Models\UploadsRepository;
use Nette\Application\Responses\FileResponse;
use Nette\Application\UI\Control;

/** @property-read TasksTemplate $template */
class TasksTableControl extends Control
{
    /** @var callable(FileResponse): void */
    public $onFileDownload;

    public function __construct(private UploadsRepository $uploadsRepository)
    {
    }

    public function render(array $tasks): void
    {
        $this->template->tasks = $tasks;
        $this->template->render(__DIR__ . '/templates/TasksTable.latte');
    }

    public function getTaskFile($taskId) : ?string
    {
        $file = $this->uploadsRepository->findFile((string)$taskId);

        if($file){
            return $file->getFilename();
        }

        return null;
    }

    public function handleDownloadFile(int $taskId){
        $file = $this->uploadsRepository->findFile((string)$taskId);
        $this->onFileDownload(new FileResponse($file->getPathname()));
    }
}