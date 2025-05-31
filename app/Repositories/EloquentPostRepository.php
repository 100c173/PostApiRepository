<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentPostRepository implements PostRepositoryInterface
{
    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getById(int $postId): ?Post
    {
        return $this->model->findOrFail($postId);
    }

    public function create(array $data): Post
    {
        return $this->model->create($data);
    }

    public function update(int $postId, array $data): bool
    {
        $post = $this->model->findOrFail($postId);
        return $post->update($data);
    }

    public function delete(int $postId): bool
    {
        return $this->model->destroy($postId);
    }
}