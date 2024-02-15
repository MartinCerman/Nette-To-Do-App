<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\TaskForm\TaskForm;
use App\Components\TasksTable\TasksTableControl;
use App\Factories\TasksTableControlFactory;
use App\Models\TasksRepository;
use App\Models\TaskStatus;
use App\Models\TasksTemplate;
use App\Models\UploadsRepository;
use Nette\Application\Responses\FileResponse;
use Nette\Application\UI\Form;
use Nette;

/** @property-read TasksTemplate $template */
final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private TasksRepository   $tasksRepository,
        private UploadsRepository $uploadsRepository,
        private TasksTableControlFactory $tasksTableControlFactory,
    )
    {
        parent::__construct();
    }

    public function createComponentTasksTable(): TasksTableControl
    {
        $tasksTable = $this->tasksTableControlFactory->create();
        $tasksTable->onFileDownload[] = $this->onTasksTableFileDownload(...);
        return $tasksTable;
    }

    public function onTasksTableFileDownload(FileResponse $fileResponse): void
    {
        $this->sendResponse($fileResponse);
    }

    public function renderDefault(): void
    {
        $this->template->tasks['active'] = $this->tasksRepository
            ->getAll(TaskStatus::Active);

        $this->template->tasks['completed'] = $this->tasksRepository
            ->getAll(TaskStatus::Completed);
    }

    protected function createComponentInsertTaskForm(): Form
    {
        $form = new TaskForm();
        $form->onSuccess[] = $this->formSucceeded(...);

        return $form;
    }

    public function formSucceeded(Form $form, $data): void
    {
        $taskId = $this->tasksRepository->addTask($data->name, $data->description);

        if($data->upload->hasFile()){
            $this->uploadsRepository->addFile((string)$taskId, $data->upload);
        }

        $this->flashMessage("Uloha přidána s id $taskId.");

        $this->redirect('Home:');
    }
}
