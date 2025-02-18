<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PostinganResource;
use App\Models\Postingan;
use Illuminate\Http\Request;

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
    public function index()
    {
        return PostinganResource::collection(Postingan::all());
    }
}
