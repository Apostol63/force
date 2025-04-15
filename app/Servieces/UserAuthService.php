<?php

declare(strict_types=1);

namespace App\Servieces;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserAuthService
{
    public function auth(User $user): string
    {
        Auth::login($user);

        return $user->createToken('auth_token')->plainTextToken;
    }
}
