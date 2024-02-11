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

    public function getAll(TaskStatusFilter $filterTasks = TaskStatusFilter::All): Selection
    {
        $tasks = $this->database->table('tasks');

        if ($filterTasks !== TaskStatusFilter::All) {
            $tasks->where('isCompleted', $filterTasks);
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