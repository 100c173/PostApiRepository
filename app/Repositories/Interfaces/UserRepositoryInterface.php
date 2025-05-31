<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $userId): ?User;
    public function create(array $userData): User;
    public function update(int $userId, array $userData): bool;
    public function delete(int $userId): bool;
    public function findByEmail(string $email): ?User;
}