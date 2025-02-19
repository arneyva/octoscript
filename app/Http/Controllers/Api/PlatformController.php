<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlatformResource;
use App\Http\Resources\PostinganResource;
use App\Models\Platform;
use App\Models\Postingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
/**
 * @OA\Tag(
 *     name="Platforms",
 *     description="Endpoints for Platforms"
 * )
 */
class PlatformController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $platform = Platform::query()->paginate($request->query('limit') ?? 10);
        return $this->successResponse(PlatformResource::paginate( $platform));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:platforms'
        ]);
        try {
            DB::beginTransaction();
            $platform = Platform::create([
                'name' => $validated['name'],
            ]);
            DB::commit();
            return $this->successResponse(new PlatformResource($platform));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
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
