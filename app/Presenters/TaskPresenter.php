<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\TasksRepository;
use http\Exception\BadQueryStringException;
use Nette\Application\Attributes\Parameter;
use Nette\Application\Attributes\Persistent;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Database\Table\ActiveRow;
use Tracy\Debugger;
use Tracy\OutputDebugger;

/**
 *  TaskPresenter allows CRUD requests for a single task.
 *
 *  Task is represented by its $taskId (int) passed in query string, if it's not
 *  provided, not found or in an incorrect format, an exception is thrown.
 */
class TaskPresenter extends Presenter
{
    #[Persistent]
    public int $taskId;

    public function __construct(private TasksRepository $tasksRepository)
    {
        parent::__construct();
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

        $this->template->task = $task;
        return true;
    }

    public function renderDefault(int $taskId): void
    {
    }

    public function renderEdit(int $taskId): void
    {
        $this->getComponent('editTaskForm')
            ->setDefaults($this->template->task);
    }

    public function renderDelete(int $taskId): void
    {
        $this->getComponent('deleteTaskForm')
            ->setDefaults($this->template->task);
    }

    public function createComponentEditTaskForm(): Form
    {
        $form = new Form();

        $form->addText('name', 'Název úlohy')
            ->setRequired('Musíte zadat alespoň název úlohy.')
            ->setHtmlAttribute('placeholder', 'Název');

        $form->addTextArea('description', 'Popis')
            ->setHtmlAttribute('placeholder', 'Popis');

        $form->addCheckbox('isCompleted', 'Splňeno');

        $form->addSubmit('submit', 'Uložit');
        $form->onSuccess[] = $this->editTaskFormSucceeded(...);

        return $form;
    }

    public function createComponentDeleteTaskForm(): Form
    {
        $form = new Form();

        $form->addHidden('id');
        $form->addSubmit('submit', 'Smazat');

        $form->onSuccess[] = $this->deleteTaskFormSucceeded(...);

        return $form;
    }

    public function editTaskFormSucceeded(Form $form, array $task): void
    {
        $task['id'] = $this->getParameter('taskId');
        $this->tasksRepository->updateTask($task);
        $this->flashMessage("Úloha s názvem {$task['name']} byla upravena.");
        $this->redirect('Home:');
    }

    public function deleteTaskFormSucceeded(Form $form, $task): void
    {
        $this->tasksRepository->removeTask((int)$task['id']);
        $this->flashMessage('Úloha byla smazána.');
        $this->redirect('Home:');
    }
}