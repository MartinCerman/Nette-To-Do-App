<?php

declare(strict_types=1);

namespace App\Models;

use Doctrine\ORM\EntityManager;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Doctrine\DBAL\Connection;

class TasksRepository
{
    public function __construct(private Explorer $database, private EntityManager $entityManager)
    {
    }

    /**
     * Reads tasks from database.
     *
     * @return Selection Returns all tasks if $status is not provided,
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

    public function getById(int $taskId): \App\Models\Database\Entity\Task
    {
        $repository = $this->entityManager->getRepository(\App\Models\Database\Entity\Task::class);

        return $repository->find($taskId);
    }

    public function updateTask(array $task): void
    {
        //TODO - change to Doctrine
        $this->database->table('tasks')
            ->get($task['id'])
            ->update($task);
    }

    public function removeTask(int $taskId): void
    {
        //TODO - change to Doctrine
        $this->database->table('tasks')
            ->get($taskId)
            ->delete();
    }
}