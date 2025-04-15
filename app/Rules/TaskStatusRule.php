<?php

declare(strict_types=1);

namespace App\Rules;

use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\InvokableRule;

class TaskStatusRule implements InvokableRule
{
    public function __invoke($attribute, $value, $fail): bool
    {

    }
}
