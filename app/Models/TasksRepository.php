<?php

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

/**
 * @property-read TasksTemplate $template
 */
class TasksRepository
{
    public function __construct(private Explorer $database)
    {
    }

    public function getAll(): Selection
    {
        return $this->database->table('tasks');
    }

    public function addTask($name, $description): void
    {
        $this->database->table('tasks')
            ->insert([
                'name' => $name,
                'description' => $description
            ]);
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