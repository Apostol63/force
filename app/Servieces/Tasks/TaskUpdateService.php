<?php

declare(strict_types=1);

namespace App\Servieces\Tasks;

use App\Models\Task;
use App\Http\Requests\Task\Api\TaskUpdateRequest;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskUpdateService
{
    public function __construct(
        private TaskRepositoryInterface $repository
    )
    {}

    public function handle(TaskUpdateRequest $request, string $taskId): ?Task
    {
        $data = $this->prepareTask($request, $taskId);
        return $this->repository->update($data);
    }

    private function prepareTask(TaskUpdateRequest $request, string $taskId): array
    {
        return [
            'id' => $taskId,
            'title' => $request->title,
            'description' => $request->description ?? '',
            'status' => $request->status,
            'user_id' => auth()->id()
        ];
    }
}
