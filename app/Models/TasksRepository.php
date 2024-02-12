<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class TasksRepository
{
    public function __construct(private Explorer $database)
    {
    }

    /**
     * Reads tasks from database.
     *
     * @return Selection Returns all tasks if $status is not provided,
     * otherwise returns tasks with provided $status.
     */
    public function getAll(?TaskStatus $status = null): Selection
    {
        $tasks = $this->database->table('tasks');

        if ($status !== null) {
            $tasks->where('isCompleted', $status);
        }

        return $tasks;
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

    public function getById(int $taskId): ?ActiveRow
    {
        return $this->database->table('tasks')
            ->get($taskId);
    }

    public function updateTask(array $task): void
    {
        $this->database->table('tasks')
            ->get($task['id'])
            ->update($task);
    }

    public function removeTask(int $taskId): void
    {
        $this->database->table('tasks')
            ->get($taskId)
            ->delete();
    }
}