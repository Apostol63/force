<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth\Api;

use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'password.confirmed' => 'Пароль и подтвержденный пароль не совпадают.',
            'email.unique' => 'Пользователь с таким email уже существует.',
        ];
    }
}
