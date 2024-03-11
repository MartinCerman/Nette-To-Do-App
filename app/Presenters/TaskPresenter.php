<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\TaskForm\TaskForm;
use App\Models\TaskRepository;
use App\Models\TaskTemplate;
use App\Models\UploadsRepository;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Utils\ArrayHash;

/**
 *  TaskPresenter allows CRUD requests for a single task.
 *
 *  Task is represented by its $taskId (int) passed in query string, if it's not
 *  provided, not found or in an incorrect format, an exception is thrown.
 *
 * @property-read TaskTemplate $template
 */
class TaskPresenter extends Presenter
{
    #[Persistent]
    public int $taskId;

    public function __construct(
        private TaskRepository    $tasksRepository,
        private UploadsRepository $uploadsRepository,
    )
    {
        parent::__construct();
    }

    public function beforeRender()
    {
       if(!$this->getUser()->isLoggedIn()){
           $this->redirect('Home:in');
       }
    }

    public function loadState(array $params): void
    {
        parent::loadState($params);
        $taskId = (int)$params['taskId'];

        $this->loadTask($taskId) ?:
            $this->error("Task id $taskId not found.");
    }

    /**
     * Loads a task with id $id from repository into template.
     *
     * @param int $id
     * @return bool Returns true if the task is loaded successfully, false otherwise.
     */
    private function loadTask(int $id): bool
    {
        $task = $this->tasksRepository->getById($id);

        if (!$task) {
            return false;
        }

        if($task->getUser()?->getId() !== $this->user->getId()){
            return false;
        }

        $this->template->task = $task;
        return true;
    }

    public function renderDefault(int $taskId): void
    {
    }

    public function renderEdit(int $taskId): void
    {
        $this->getComponent('editTaskForm')
            ->setDefaults($this->template->task->toArray());
    }

    /**
     * Removes a task and its associated folder.
     */
    public function handleDelete(): void
    {
        $this->tasksRepository->removeTask($this->taskId);

        $this->uploadsRepository->deleteFolder((string)$this->taskId, $this->user->getId());

        $this->flashMessage('Úloha byla smazána.');
        $this->redirect('Home:');
    }

    public function createComponentEditTaskForm(): TaskForm
    {
        $form = new TaskForm();
        $form->onSuccess[] = $this->editTaskFormSucceeded(...);

        return $form;
    }

    public function editTaskFormSucceeded(ArrayHash $data): void
    {
        $taskData = [
            'name' => $data->name,
            'description' => $data->description,
            'isCompleted' => $data->isCompleted
        ];

        $this->tasksRepository->updateTask($this->taskId, $taskData);


        if ($data->upload->hasFile()) {
            $userFolder = $this->user->getId() . '/' . $this->taskId;
            $this->uploadsRepository->addFile($userFolder, $data->upload);
        }

        $this->flashMessage("Úloha s názvem {$data->name} byla upravena.");
        $this->redirect('Home:');
    }
}