<?php

declare(strict_types=1);

namespace App\Http\Requests\Task\Api;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskUpdateRequest extends FormRequest
{
    protected ?Task $task = null;

    protected function prepareForValidation()
    {
        $this->task = Task::find($this->route('id'));

        if (!$this->task) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Такой задачи не существует',
                ], 404)
            );
        }
    }

    public function task(): Task
    {
        return $this->task;
    }

    public function rules() {
        $taskId = $this->route('id');

        return [
            'title' => [
                'required',
                'string',
                Rule::unique('tasks', 'title')->ignore($taskId),
            ],
            'description' => ['required', 'string'],
            'status' => ['required', new Enum(TaskStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'Такой заголовок задачи уже существует',
            'status' => 'Недопустимое значение статуса.',
        ];
    }
}
