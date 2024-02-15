<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Database\Entity\Task;
use Doctrine\ORM\EntityManager;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Doctrine\DBAL\Connection;
use Nette\Utils\ArrayHash;
use Nettrine\ORM\EntityManagerDecorator;

class TasksRepository
{
    public function __construct(
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
        $task = new Task();
        $task->setName($name);
        $task->setDescription($description);

        $this->entityManager->persist($task);

        $this->entityManager->flush();

        return $task->getId();
    }

    public function getById(int $taskId): Task
    {
        return $this->entityManager->find(Task::class, $taskId);
    }

    public function updateTask(int $taskId, array $taskData): void
    {
        $task = $this->entityManager->find(Task::class, $taskId);

        if ($task === null) {
            throw new \InvalidArgumentException("Task with id '{$taskId}' not found.");
        }

        foreach ($taskData as $property => $value) {
            $setter = 'set' . ucfirst($property);
            if (method_exists($task, $setter)) {
                $task->$setter($value);
            } else {
                throw new \InvalidArgumentException("Property $property not found.");
            }
        }

        $this->entityManager->flush();
    }

    public function removeTask(int $taskId): void
    {
        $task = $this->entityManager->find(Task::class, $taskId);
        if ($task) {
            $this->entityManager->remove($task);
            $this->entityManager->flush();
        }
    }
}