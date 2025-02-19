<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
/**
 * @OA\Tag(
 *     name="Brands",
 *     description="Endpoints for Brands"
 * )
 */
class BrandController extends ApiController
{
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brand = Brand::query()->paginate($request->query('limit') ?? 10);
        return $this->successResponse(BrandResource::paginate( $brand));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:brands'
        ]);
        try {
            DB::beginTransaction();
            $brand = Brand::create([
                'name' => $validated['name'],
            ]);
            DB::commit();

            return $this->successResponse(new BrandResource($brand));
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
