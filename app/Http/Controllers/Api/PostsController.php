<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostsResource;
use App\Models\Posts;
use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Endpoints for managing posts"
 * )
 */
class PostsController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/postingan",
     *     summary="Get all postingan",
     *     tags={"Posts"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */
    public function index(Request $request)
    {
        $posts = Posts::query()
        ->filters($request->query()) // Panggil scopeFilters()
        ->paginate($request->query('limit') ?? 10);
            return $this->successResponse(PostsResource::paginate($posts));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
