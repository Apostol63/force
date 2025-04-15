<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function save(array $userData);
}
