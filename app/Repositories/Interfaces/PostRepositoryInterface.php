<?php

namespace App\Repositories\Interfaces;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Post;

interface PostRepositoryInterface 
{
    public function getAll(): Collection;
    public function getById(int $postId): ?Post;
    public function create(array $data): Post;
    public function update(int $postId, array $data): bool;
    public function delete(int $postId): bool;
}