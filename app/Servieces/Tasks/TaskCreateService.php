<?php

declare(strict_types=1);

namespace App\Servieces\Tasks;

use App\Models\Task;
use App\Enums\TaskStatus;
use App\Http\Requests\Task\Api\TaskCreateRequest;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskCreateService
{
    public function __construct(
        private TaskRepositoryInterface $repository
    )
    {}

    public function handle(TaskCreateRequest $request): Task
    {
        $data = $this->prepareData($request);
        return $this->repository->create($data);
    }

    private function prepareData(TaskCreateRequest $request): array
    {
        return [
            'title' => $request->title,
            'description' => $request->description ?? '',
            'status' => $request->status ?? TaskStatus::NEW,
            'user_id' => auth()->id()
        ];
    }
}
