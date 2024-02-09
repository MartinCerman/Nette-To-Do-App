<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\TasksTableControl;
use App\Models\Task;
use App\Models\TasksRepository;
use App\Models\TasksTemplate;
use Nette\Application\UI\Form;
use Nette;

/** @property-read TasksTemplate $template */
final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(private TasksRepository $tasksRepository)
    {
        parent::__construct();
    }

    public function createComponentTasksTable(): TasksTableControl
    {
        return new TasksTableControl();
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
        $form = new Form();
        $form->addText('name', 'Název úlohy')
            ->setRequired('Musíte zadat alespoň název úlohy.')
            ->setHtmlAttribute('placeholder', 'Název');
        $form->addTextArea('description', 'Popis')
            ->setHtmlAttribute('placeholder', 'Popis');
        $form->addSubmit('submit', 'Uložit');
        $form->onSuccess[] = $this->formSucceeded(...);

        return $form;
    }

    public function formSucceeded(Form $form, $data): void
    {
        $this->tasksRepository->addTask($data->name, $data->description);
        $this->redirect('Home:');
    }
}
