<?php

declare(strict_types=1);

namespace App\Servieces;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class PersonalAccessTokenService
{
    public function checkUserId(string $id): bool
    {
        return PersonalAccessToken::where('tokenable_id', $id)
            ->where('tokenable_type', User::class)
            ->exists();
    }
}
