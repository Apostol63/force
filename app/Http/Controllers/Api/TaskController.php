<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Servieces\Tasks\TaskFindService;
use App\Servieces\Tasks\TaskCreateService;
use App\Servieces\Tasks\TaskUpdateService;
use App\Http\Requests\Task\Api\TaskCreateRequest;
use App\Http\Requests\Task\Api\TaskUpdateRequest;
use App\Http\Requests\Task\Api\TaskViewAllRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private TaskCreateService $taskCreateService,
        private TaskUpdateService $taskUpdateService,
        private TaskFindService $taskFindService
    )
    {}

    public function all(TaskViewAllRequest $request)
    {
        $this->authorize('viewAny', Task::class);

        $status = $request->query('status');

        if ($status) {
            $tasks = $this->taskFindService->findAdllByUserIdFilteredStatus(auth()->id(), $status);
        } else {
            $tasks = $this->taskFindService->findAllByUserId(auth()->id());
        }

        return response()->json(
            [
                'data'=> $tasks
            ],
            200,
        );
    }

    public function create(TaskCreateRequest $request): JsonResponse
    {
        $task = $this->taskCreateService->handle($request);

        $this->authorize('create', Task::class);

        return response()->json(['data' => ['title' => $task['title'], 'description' => $task['description'], 'status' => $task['status']]], 200);
    }

    public function update(TaskUpdateRequest $request, string $id): JsonResponse
    {
        $task = $request->task();

        $this->authorize('update', $task);

        $task = $this->taskUpdateService->handle($request, $id);

        if (!$task) {
            return response()->json([
                'message' => 'Такой задачи не существует',
            ], 404);
        }

        return response()->json(['data' => ['title' => $task['title'], 'description' => $task['description'], 'status' => $task['status']]], 200);
    }

    public function delete(string $id)
    {
        $task = $this->taskFindService->findById($id);

        if (!$task) {
            return response()->json([
                'message' => 'Такой задачи не существует',
            ], 404);
        }

        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'message' => 'Задача успешно удалена',
        ], 200);
    }
}
