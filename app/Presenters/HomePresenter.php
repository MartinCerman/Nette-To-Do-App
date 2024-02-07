<?php

declare(strict_types=1);

namespace App\Presenters;

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

    public function renderDefault(): void
    {
        $this->template->tasks = $this->tasksRepository->getAll()->fetchAll();
    }

    protected function createComponentInsertTaskForm(): Form {
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
