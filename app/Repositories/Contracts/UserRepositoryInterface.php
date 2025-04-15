<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function save(array $userData): User;

    public function findByEmail(string $email): ?User;
}
