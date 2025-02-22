<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="Octroscript",
 *     version="1.0.0"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token"
 * )
 */
class ApiController extends Controller
{
    public static function successResponse($data = [], $message = 'success')
    {
        return response()->json([
            'success' => true,
            'validation' => (object) [],
            'data' => (object) $data,
            'message' => $message,
        ]);
    }

    public static function errorResponse(string $message = 'failed', int $code = 400)
    {
        return response()->json([
            'success' => false,
            'validation' => (object) [],
            'data' => (object) [],
            'message' => $message,
        ], $code);
    }

    public static function errorValidation(array $validation, string $message = 'validation error')
    {
        return response()->json([
            'success' => false,
            'validation' => (object) $validation,
            'data' => (object) [],
            'message' => $message,
        ], 400);
    }
}
