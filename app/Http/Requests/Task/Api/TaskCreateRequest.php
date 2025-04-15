<?php

declare(strict_types=1);

namespace App\Http\Requests\Task\Api;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class TaskCreateRequest extends FormRequest
{
    public function rules() {
        return [
            'title' => ['required', 'string', 'unique:'.Task::class],
            'status' => [new Enum(TaskStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Название задачи обязательно для создания',
            'title.unique' => 'Такой заголовок задачи уже существует',
            'status' => 'Недопустимое значение статуса.',
        ];
    }
}
