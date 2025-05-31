<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;

        $this->middleware('auth:sanctum')->except(['index','show']);
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = $this->postRepository->getAll();
            return response()->json(['data' => $posts], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching posts: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch posts'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
            $post = $this->postRepository->create($request->validated());
            return response()->json(['data' => $post, 'message' => 'Post created successfully'], 201);
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create post'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = $this->postRepository->getById($id);
            return response()->json(['data' => $post], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching post ' . $id . ': ' . $e->getMessage());
            return response()->json(['message' => 'Post not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        try {
            if ($this->postRepository->update($id, $request->validated())) {
                return response()->json(['message' => 'Post updated successfully'], 200);
            } else {
                return response()->json(['message' => 'Post not found'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error updating post ' . $id . ': ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update post'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if ($this->postRepository->delete($id)) {
                return response()->json(['message' => 'Post deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Post not found'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting post ' . $id . ': ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete post'], 500);
        }
    }
}
