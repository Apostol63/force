<?php

declare(strict_types=1);

namespace App\Http\Requests\Task\Api;

use App\Enums\TaskStatus;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class TaskViewAllRequest extends FormRequest
{
    public function rules() {
        return [
            'status' => [new Enum(TaskStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'status' => 'Недопустимое значение статуса.',
        ];
    }
}
