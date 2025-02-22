<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\PostsResource;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Endpoints for Posts"
 * )
 */
class PostsController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get all postingan",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *  *     @OA\Parameter(
     *         name="brand_id",
     *         in="query",
     *         description="Filter by brand ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="platform_id",
     *         in="query",
     *         description="Filter by platform ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"pending", "approved", "rejected"})
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $posts = Posts::query()
            ->filters($request->query())
            ->paginate($request->query('limit') ?? 10);
        return $this->successResponse(PostsResource::paginate($posts));
    }
    /**
     * @OA\Post(
     *     path="/api/posts/store",
     *     summary="Create a new post",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"post_title", "brand_id", "platform_id", "due_date", "payment", "status"},
     *             @OA\Property(property="post_title", type="string", example="New Post"),
     *             @OA\Property(property="brand_id", type="integer", example=1),
     *             @OA\Property(property="platform_id", type="integer", example=2),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-12-31"),
     *             @OA\Property(property="payment", type="number", example=50000),
     *             @OA\Property(property="status", type="string", enum={"pending", "approved", "rejected"}, example="pending")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Post created successfully"),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_title' => 'required|string|max:255',
            'brand_id' => 'required|integer|exists:brands,id',
            'platform_id' => 'required|integer|exists:platforms,id',
            'due_date' => 'required|date',
            'payment' => 'required|numeric',
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors()->toArray());
        }

        $post = Posts::create($request->only([
            'post_title',
            'brand_id',
            'platform_id',
            'due_date',
            'payment',
            'status'
        ]));

        return $this->successResponse(new PostsResource($post), 'Post created successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Update an existing post",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="post_title", type="string", example="Updated Post"),
     *             @OA\Property(property="brand_id", type="integer", example=1),
     *             @OA\Property(property="platform_id", type="integer", example=2),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-12-31"),
     *             @OA\Property(property="payment", type="number", example=60000),
     *             @OA\Property(property="status", type="string", enum={"pending", "approved", "rejected"}, example="approved")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Post updated successfully"),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function update(Request $request, string $id)
    {
        $post = Posts::find($id);

        if (!$post) {
            return $this->errorResponse('Post not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'post_title' => 'sometimes|string|max:255',
            'brand_id' => 'sometimes|integer|exists:brands,id',
            'platform_id' => 'sometimes|integer|exists:platforms,id',
            'due_date' => 'sometimes|date',
            'payment' => 'sometimes|numeric',
            'status' => 'sometimes|string|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors()->toArray());
        }

        $post->update($request->only([
            'post_title',
            'brand_id',
            'platform_id',
            'due_date',
            'payment',
            'status'
        ]));

        return $this->successResponse(new PostsResource($post), 'Post updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete a post",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the post",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Post deleted successfully"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=404, description="Post not found")
     * )
     */
    public function destroy(string $id)
    {
        $post = Posts::find($id);

        if (!$post) {
            return $this->errorResponse('Post not found', 404);
        }

        $post->delete();

        return $this->successResponse([], 'Post deleted successfully');
    }
}
