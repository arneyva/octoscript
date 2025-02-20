<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;


/**
 * @OA\Tag(
 *     name="Brands",
 *     description="Endpoints for Brands"
 * )
 */
class BrandController extends ApiController
{

    /**
     * @OA\Get(
     *     path="/api/brand",
     *     summary="Get all brands",
     *     tags={"Brands"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     * )
     */
    public function index(Request $request)
    {
        $brand = Brand::query()->paginate($request->query('limit') ?? 10);
        return $this->successResponse(BrandResource::paginate($brand));
    }

    /**
     * @OA\Post(
     *     path="/api/brand/store",
     *     summary="Create a new brand",
     *     tags={"Brands"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *            @OA\Property(property="name", type="string", example="BrandName")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Brand created successfully"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:brands'
        ]);

        if ($validator->fails()) {
            return self::errorValidation($validator->errors()->toArray());
        }
        try {
            DB::beginTransaction();
            $brand = Brand::create([
                'name' => $request->name,
            ]);
            DB::commit();
            return $this->successResponse(new BrandResource($brand));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('brands', 'name')->ignore($brand->id), // Pastikan name unik kecuali untuk brand saat ini
                ],
            ]);
            $brand->update([
                'name' => $request->input('name'),
            ]);
            return $this->successResponse($brand, 'Brand updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Brand not found', 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorValidation($e->errors(), 'Validation error');
        } catch (Exception $e) {
            return $this->errorResponse('Something went wrong', 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();
            return $this->successResponse(null, 'Brand deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Brand not found', 404);
        }
    }
}
