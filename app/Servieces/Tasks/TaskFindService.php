<?php

declare(strict_types=1);

namespace App\Servieces\Tasks;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskFindService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    )
    {}

    public function findById(string $id): ?Task
    {
        return $this->taskRepository->findById($id);
    }

    public function findAllByUserId(string $id): array
    {
        $tasks = $this->taskRepository->findAllByUserId($id);

        return $this->prepareAnswer($tasks);
    }

    public function findAdllByUserIdFilteredStatus(string $id, string $status)
    {
        $tasks = $this->taskRepository->fundAllByUserIdFilteredStatus($id, $status);

        return $this->prepareAnswer($tasks);
    }

    private function prepareAnswer(Collection $tasks): array
    {
        if ($tasks->isEmpty()) {
            return [];
        }

        return $tasks->map(function ($task) {
           return [
               'title' => $task->title,
               'description' => $task->description,
               'status' => $task->status
           ];
        })->toArray();
    }
}
