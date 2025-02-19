<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostinganResource;
use App\Models\Postingan;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Endpoints for managing posts"
 * )
 */
class PostinganController extends ApiController
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
        $postingan = Postingan::query()
        ->filters($request->query()) // Panggil scopeFilters()
        ->paginate($request->query('limit') ?? 10);
            return $this->successResponse(PostinganResource::paginate($postingan));
    }
}