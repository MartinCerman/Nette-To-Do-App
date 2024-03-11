<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Components\TaskForm\TaskForm;
use App\Components\TasksTable\TasksTableControl;
use App\Factories\TasksTableControlFactory;
use App\Models\TaskRepository;
use App\Models\TaskStatus;
use App\Models\TaskTemplate;
use App\Models\UploadsRepository;
use Doctrine\DBAL\Exception;
use Nette\Application\Responses\FileResponse;
use Nette\Application\UI\Form;
use Nette;

/** @property-read TaskTemplate $template */
final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private TaskRepository           $tasksRepository,
        private UploadsRepository        $uploadsRepository,
        private TasksTableControlFactory $tasksTableControlFactory,
    )
    {
        parent::__construct();
    }

    private function requireLoggedUser(): void
    {
        $user = $this->getUser();

        if (!$user->isLoggedIn()) {
            $this->redirect(':in');
        }
    }

    public function createComponentTasksTable(): TasksTableControl
    {
        $tasksTable = $this->tasksTableControlFactory->create();
        $tasksTable->setUserId($this->user->getId());
        $tasksTable->onFileDownload[] = $this->onTasksTableFileDownload(...);
        return $tasksTable;
    }

    public function onTasksTableFileDownload(FileResponse $fileResponse): void
    {
        $this->sendResponse($fileResponse);
    }

    public function renderDefault(): void
    {
        $this->requireLoggedUser();

        $this->template->tasks['active'] = $this->tasksRepository
            ->getTasks(TaskStatus::Active, $this->user->getId());

        $this->template->tasks['completed'] = $this->tasksRepository
            ->getTasks(TaskStatus::Completed, $this->user->getId());
    }

    protected function createComponentInsertTaskForm(): Form
    {
        $form = new TaskForm();
        $form->onSuccess[] = $this->formSucceeded(...);

        return $form;
    }

    protected function createComponentSignInForm(): Form
    {
        $form = new Form();
        $form->addEmail('email', 'E-mail');
        $form->addPassword('password', 'Heslo');
        $form->addSubmit('send');

        $form->onSuccess[] = $this->signInFormSucceeded(...);

        return $form;
    }

    protected function signInFormSucceeded(Form $form, array $data)
    {
        try {
            $this->user->login($data['email'], $data['password']);
            $this->redirect(':default');
        } catch (Nette\Security\AuthenticationException $ex) {
            $form->addError("Špatný e-mail nebo heslo.");
        }
    }

    public function formSucceeded(Form $form, $data): void
    {
        if (!$this->user->isLoggedIn()) {
            throw new \Exception('You must be signed in for this operation');
        }

        $taskId = $this->tasksRepository->addTask($data->name, $data->description, $this->user->getId());

        $userFolder = $this->user->getId() . '/' . $taskId;

        if ($data->upload->hasFile()) {
            $this->uploadsRepository->addFile($userFolder, $data->upload);
        }

        $this->flashMessage("Uloha přidána s id $taskId.");

        $this->redirect('Home:');
    }

    public function handleSignOut(): void
    {
        $this->user->logout();
    }
}
