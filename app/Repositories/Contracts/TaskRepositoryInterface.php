<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function create(array $dataTask): Task;
    public function findById(string $id): ?Task;
    public function update(array $dataTask): ?Task;
    public function findAllByUserId(string $id): Collection;
    public function fundAllByUserIdFilteredStatus(string $id, string $status):Collection;
}
