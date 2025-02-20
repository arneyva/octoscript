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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
