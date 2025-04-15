<?php

declare(strict_types=1);

namespace App\Servieces;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserFindService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {}

    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }
}
