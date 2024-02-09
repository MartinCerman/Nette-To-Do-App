<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class TasksRepository
{
    public const GET_ACTIVE = 0;
    public const GET_COMPLETED = 1;
    public const GET_ALL = 2;

    public function __construct(private Explorer $database)
    {
    }

    public function getAll(int $filterTasks = self::GET_ALL): Selection
    {
        $tasks = $this->database->table('tasks');

        if($filterTasks !== self::GET_ALL){
            $tasks->where('isCompleted', $filterTasks);
        }

        return $tasks;
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