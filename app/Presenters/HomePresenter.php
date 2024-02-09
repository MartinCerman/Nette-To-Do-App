<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\TaskForm;
use App\Components\TasksTableControl;
use App\Factories\TasksTableControlFactory;
use App\Models\Task;
use App\Models\TasksRepository;
use App\Models\TasksTemplate;
use App\Models\UploadsRepository;
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
        return $this->tasksTableControlFactory->create();
    }

    public function renderDefault(): void
    {
        $this->template->tasks['active'] = $this->tasksRepository
            ->getAll(TasksRepository::GET_ACTIVE)
            ->fetchAll();

        $this->template->tasks['completed'] = $this->tasksRepository
            ->getAll(TasksRepository::GET_COMPLETED)
            ->fetchAll();
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

        $this->redirect('Home:');
    }
}
