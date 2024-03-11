<?php

declare(strict_types=1);

namespace App\Components\TasksTable;

use App\Models\TaskTemplate;
use App\Models\UploadsRepository;
use Nette\Application\Responses\FileResponse;
use Nette\Application\UI\Control;

/** @property-read TaskTemplate $template */
class TasksTableControl extends Control
{
    /** @var callable(FileResponse): void */
    public $onFileDownload;

    private int $userId;

    public function __construct(private UploadsRepository $uploadsRepository)
    {
    }

    public function setUserId(int $id): void
    {
        $this->userId = $id;
    }

    public function render(array $tasks): void
    {
        $this->template->tasks = $tasks;
        $this->template->render(__DIR__ . '/templates/TasksTable.latte');
    }

    public function getTaskFile($taskId): ?string
    {
        $userFolder = $this->userId . '/' . $taskId;
        $file = $this->uploadsRepository->findFile($userFolder);

        if ($file) {
            return $file->getFilename();
        }

        return null;
    }

    public function handleDownloadFile(int $taskId)
    {
        $userFolder = $this->userId . '/' . $taskId;
        $file = $this->uploadsRepository->findFile($userFolder);
        $this->onFileDownload(new FileResponse($file->getPathname()));
    }
}