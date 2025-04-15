<?php

declare(strict_types=1);

namespace App\Servieces;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRegisterService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {}

    public function saveUser(array $userData): User
    {
        $user = $this->userRepository->save($userData);

        $this->newRegisterEvent($user);

        return $user;
    }

    private function newRegisterEvent(User $user): void
    {
        event(new Registered($user));
    }
}
