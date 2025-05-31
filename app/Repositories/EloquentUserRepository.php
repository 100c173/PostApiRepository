<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentUserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getById(int $userId): ?User
    {
        return $this->model->findOrFail($userId);
    }

    public function create(array $userData): User
    {
        return $this->model->create($userData);
    }

    public function update(int $userId, array $userData): bool
    {
        $user = $this->model->findOrFail($userId);
        return $user->update($userData);
    }

    public function delete(int $userId): bool
    {
        return $this->model->destroy($userId);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
}