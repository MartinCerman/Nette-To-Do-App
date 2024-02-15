<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Database\Entity\Task;
use Doctrine\ORM\EntityManager;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Doctrine\DBAL\Connection;
use Nettrine\ORM\EntityManagerDecorator;

class TasksRepository
{
    public function __construct(
        private Explorer $database,
        private EntityManagerDecorator $entityManager)
    {
    }

    /**
     * Reads tasks from database.
     *
     * @return array Returns all tasks if $status is not provided,
     * otherwise returns tasks with provided $status.
     */
    public function getAll(?TaskStatus $status = null): array
    {
        $repository = $this->entityManager->getRepository(\App\Models\Database\Entity\Task::class);

        if ($status !== null) {
            return $repository->findBy(['isCompleted' => $status->value]);
        }

        return $repository->findAll();
    }

    public function addTask($name, $description): int
    {
        $insertedRow = $this->database->table('tasks')
            ->insert([
                'name' => $name,
                'description' => $description
            ]);

        return $insertedRow->id;
    }

    public function getById(int $taskId): Task
    {
        return $this->entityManager->find(Task::class, $taskId);
    }

    public function updateTask(array $taskData): void
    {
        $this->database->table('tasks')
            ->get($taskData['id'])
            ?->update($taskData);
    }

    public function removeTask(int $taskId): void
    {
        $task = $this->entityManager->find(Task::class, $taskId);
        if($task){
            $this->entityManager->remove($task);
            $this->entityManager->flush();
        }
    }
}