<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlatformResource;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

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
        return $this->successResponse(PlatformResource::paginate($platform));
    }

    /**
     * @OA\Post(
     *     path="/api/platform/store",
     *     summary="Create a new platform",
     *     tags={"Platforms"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="New Platform")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Platform created successfully",
     *     ),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:platforms'
        ]);

        if ($validator->fails()) {
            return self::errorValidation($validator->errors()->toArray());
        }
        try {
            DB::beginTransaction();
            $platforms = Platform::create([
                'name' => $request->name,
            ]);
            DB::commit();
            return $this->successResponse(new PlatformResource($platforms));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/platform/{id}",
     *     summary="Update an existing platform",
     *     tags={"Platforms"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Platform Name")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Platform updated successfully"),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=404, description="Platform not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $platforms = Platform::findOrFail($id);
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('platforms', 'name')->ignore($platforms->id), // Pastikan name unik kecuali untuk brand saat ini
                ],
            ]);
            $platforms->update([
                'name' => $request->input('name'),
            ]);
            return $this->successResponse($platforms, 'platforms updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('platforms not found', 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorValidation($e->errors(), 'Validation error');
        } catch (Exception $e) {
            return $this->errorResponse('Something went wrong', 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/platform/{id}",
     *     summary="Delete a platform",
     *     tags={"Platforms"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Platform deleted successfully"),
     *     @OA\Response(response=404, description="Platform not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function destroy($id)
    {
        try {
            $platforms = Platform::findOrFail($id);
            $platforms->delete();
            return $this->successResponse(null, 'platforms deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('platforms not found', 404);
        }
    }
}
