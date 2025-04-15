<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function create(array $dataTask): Task
    {
        return Task::create($dataTask);
    }

    public function findById(string $id): ?Task
    {
        return Task::find($id);
    }

    public function update(array $dataTask): ?Task
    {
        $task = $this->findById($dataTask['id']);

        if (!$task) {
            return null;
        }

        $task->update([
            'title' => $dataTask['title'],
            'description' => $dataTask['description'],
            'status' => $dataTask['status'],
            'user_id' => $dataTask['user_id'],
        ]);

        return $task;
    }

    public function findAllByUserId(string $id): Collection
    {
        return Task::query()->where('user_id', $id)->get();
    }

    public function fundAllByUserIdFilteredStatus(string $id, string $status): Collection
    {
        return Task::query()->where('user_id', $id)->where('status', $status)->get();
    }
}
